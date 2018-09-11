<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\Application;
use app\models\Applicant;
use app\models\Choice;
use app\models\Prefecture;
use League\Csv\Writer;
use yii\data\ArrayDataProvider;
use kartik\mpdf\Pdf;
use app\models\AuditLog;
use app\models\ChoiceSearch;

class AdminController extends \yii\web\Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'clear-data' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'overview', 'view-candidates', 'view-applications', 'print-applications', 'view-denials', 'print-denials'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return \Yii::$app->user->identity->isSupervisor();
                        }
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return \Yii::$app->user->identity->isAdmin();
                        }
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index', [
                'enable_applications' => (\app\models\Config::getConfig('enable_applications') === 1),
                'enable_data_load' => (\app\models\Config::getConfig('enable_data_load') === 1),
                'enable_date_unload' => (\app\models\Config::getConfig('enable_data_unload') === 1),
                'admin' => \Yii::$app->user->identity->isAdmin(),
                'supervisor' => \Yii::$app->user->identity->isSupervisor()
        ]);
    }

    public function actionClearData()
    {
        Yii::trace('Clearing data', 'admin');

        if (($status = \Yii::$app->adminHelper->clearData()) === true) {
            Yii::$app->session->addFlash('success', "Ολοκληρώθηκε η εκκαθάριση των στοιχείων.");
        } else {
            Yii::$app->session->addFlash('danger', "Προέκυψε σφάλμα κατά την εκκαθάριση των στοιχείων. <strong>Παρακαλώ ελέγξτε τα στοιχεία!</strong>. Το μύνημα λάθους από τη βάση δεδομένων ήταν: {$status}");
        }
        return $this->redirect(['index']);
    }

    public function actionOverview()
    {
        Yii::trace('Applications overview', 'admin');

        $applications = Application::find()
            ->select('applicant_id')
            ->distinct()
            ->where('deleted=0')
            ->count();

        $applicants = Applicant::find()
            ->count();

        if ($applications == 0 && $applicants == 0) {
            Yii::$app->session->addFlash('info', "Δεν εντοπίστηκε καμία/κανένας αιτούσα/αιτών και καμία ενεργή αίτηση.");
            return $this->redirect(['admin/index']);
        }

        $application_count_subquery = Application::find()
            ->select(['applicant_id', 'COUNT(id) AS items_count'])
            ->where('deleted=0')
            ->groupBy(['applicant_id']);

        $counts = (new \yii\db\Query())
            ->select(['items_count', 'COUNT(applicant_id) AS applicants_count'])
            ->from(['counts' => $application_count_subquery])
            ->orderBy(['items_count' => SORT_DESC])
            ->groupBy(['items_count'])
            ->createCommand()
            ->queryAll();

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $counts,
            'pagination' => [
                'pageSize' => 10000,
            ],
        ]);

        $choices = Choice::find()
            ->count();

        return $this->render('overview', compact(['dataProvider', 'applicants', 'applications', 'choices']));
    }

    public function actionExportCsv()
    {
        Yii::trace('Applications export to CSV', 'admin');

        $csv = Writer::createFromFileObject(new \SplTempFileObject());
        $csv->insertOne([
            'ID',
            'Α.Φ.Μ',
            'Α.Δ.Τ',
            'Ειδικότητα',
            'Σειρά προτίμησης',
            'Χρονοσφραγίδα',
            'Θέση/Κενό',
            'Ειδικότητα',
            'ID αιτούντα',
            'ID κενού',
            'ID αιτούντα (*)',
            'ID κενού (*)',
            'Στοιχεία μηχανογράφησης',
        ]);
        $data = Application::find()->joinWith(['applicant', 'choice'])
            ->where(['deleted' => 0])
            ->orderBy([
                "applicant_id" => SORT_ASC,
                "order" => SORT_ASC
            ])
            ->asArray()
            ->all(); // implements Traversable
        $exported_data = array_map(function ($v) {
            return [
                $v['id'],
                $v['applicant']['vat'],
                $v['applicant']['identity'],
                $v['applicant']['specialty'],
                $v['order'],
                $v['updated'],
                $v['choice']['position'],
                $v['choice']['specialty'],
                $v['applicant_id'],
                $v['choice_id'],
                $v['applicant']['id'],
                $v['choice']['id'],
                $v['choice']['reference']
            ];
        }, $data);
        $csv->insertAll($exported_data);
        $csv->output('ΑΙΤΗΣΕΙΣ-' . date('Y-m-d') . '.csv');
        \Yii::$app->end();
    }

    public function actionViewApplications()
    {
        Yii::trace('Applications view', 'admin');

        $dataProvider = new ArrayDataProvider(['allModels' => Applicant::find()->joinWith([
                'applications' => function (\yii\db\ActiveQuery $query) {
                    $query->andWhere(['deleted' => 0])->count() > 0;
                }
            ])->orderBy(['specialty' => SORT_ASC, 'points' => SORT_DESC])->all(),
            'pagination' => ['pageSize' => 100],
            'sort' => ['attributes' => [
                                        'points'=> [
                                             'asc' => ['specialty' => SORT_DESC, 'points' => SORT_ASC],
                                             'desc' => ['specialty' => SORT_ASC, 'points' => SORT_DESC]],
                                        'lastname', 'firstname', 'fathername', 'vat', 'identity', 'specialty']]]);

        return $this->render('view-applications', ['users' => $dataProvider]);
    }

    public function actionViewCandidates()
    {
        Yii::trace('Candidates view', 'admin');

        $dataProvider = new ArrayDataProvider([
            'allModels' => Applicant::find()->orderBy(['specialty' => SORT_ASC, 'points' => SORT_DESC])->all(),
            'pagination' => ['pageSize' => 100],
            'sort' => [
                'attributes' => [
                    'points'=> [
                        'asc' => ['specialty' => SORT_DESC, 'points' => SORT_ASC],
                        'desc' => ['specialty' => SORT_ASC, 'points' => SORT_DESC]
                    ],
                    'lastname', 'firstname', 'fathername', 'vat', 'identity', 'specialty'
                ]
            ]
        ]);

        return $this->render('view-candidates', ['users' => $dataProvider]);
    }

    /**
     * Lists all Choice models.
     * @return mixed
     */
    public function actionViewChoices()
    {
        $searchModel = new ChoiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view-choices', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    public function actionViewDenials()
    {
        Yii::trace('View denials', 'admin');

        $dataProvider = new ArrayDataProvider(['allModels' => Applicant::find()->where(['state' => 1])->all(),
                'pagination' => ['pageSize' => 100],
                'sort' => ['attributes' => ['lastname', 'firstname',  'fathername', 'vat', 'identity', 'specialty']]
        ]);
        return $this->render('view-denials', ['users' => $dataProvider,
                'sort' => ['attributes' => ['vat', 'identity', 'specialty']],
        ]);
    }

    public function actionPrintApplications($applicantId = null)
    {
        Yii::trace('Application print: ' . ($applicantId === null ? "ALL" : "for {$applicantId}"), 'admin');
        ini_set("pcre.backtrack_limit", "5000000");

        $output_filename = 'ΑΙΤΗΣΗ-ΔΗΛΩΣΗ.pdf';
        if (isset($applicantId) && is_numeric($applicantId) && intval($applicantId) > 0) {
            $users = [Applicant::findOne(['id' => $applicantId])];
            $output_filename = sprintf("ΑΙΤΗΣΗ-ΔΗΛΩΣΗ-%s.pdf", $users[0]->getFilenameLabel());
        } else {
            $users = Applicant::find()->joinWith(['applications' => function (\yii\db\ActiveQuery $query) {
                $query->andWhere(['deleted' => 0])->count() > 0;
            }])->orderBy(['specialty' => SORT_ASC, 'points' => SORT_DESC])->all();
            $output_filename = 'ΑΙΤΗΣΗ-ΔΗΛΩΣΗ-ΟΛΕΣ.pdf';
        }

        $data = [];
        for ($j = 0; $j < count($users); $j++) {
            $choices = $users[$j]->applications;

            // if no application exists, forward to create
            if (count($choices) == 0) {
                Yii::$app->session->addFlash('info', "Δεν υπάρχει αποθηκευμένη αίτηση. Μπορείτε να υποβάλλετε νέα αίτηση.");
                return $this->redirect(['apply']);
            }

            $choicesArray = \yii\helpers\ArrayHelper::toArray($choices);

            for ($i = 0; $i < count($choicesArray); $i++) {
                $choiceActRec = Choice::findOne(['id' => $choicesArray[$i]['choice_id']]);
                $prefectureId = $choiceActRec->prefecture_id;
                $choicesArray[$i]['Position'] = $choiceActRec->position;
                $choicesArray[$i]['PrefectureName'] = Prefecture::findOne(['id' => $prefectureId])->prefecture;
                $choicesArray[$i]['RegionName'] = Prefecture::findOne(['id' => $prefectureId])->region;
            }

            $provider = new \yii\data\ArrayDataProvider([
                'allModels' => $choicesArray,
                'pagination' => false
            ]);


            $data[$j]['user'] = $users[$j];
            $data[$j]['provider'] = $provider;

            // submit ts
            $last_submit_model = AuditLog::find()->withUserId($users[$j]->id)->applicationSubmits()->one();
            $data[$j]['last_submit_model'] = $last_submit_model;
        }

        $actionlogo = "file:///" . realpath(Yii::getAlias('@images/logo.jpg'));
        $pdelogo = "file:///" . realpath(Yii::getAlias('@images/pdelogo.jpg'));
        $content = $this->renderPartial('/application/print', [
            'data' => $data, 
            'actionlogo' => $actionlogo
        ]);

        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'filename' => $output_filename,
            'destination' => Pdf::DEST_DOWNLOAD,
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:16px}',
            'options' => [
                'title' => 'Περιφερειακή Διεύθυνση Πρωτοβάθμιας και Δευτεροβάθμιας Εκπαίδευσης Κρήτης',
                'defaultheaderline' => 0,
                'defaultfooterline' => 0
            ],
            'marginTop' => Yii::$app->params['pdf']['marginTop'],
            'marginBottom' => Yii::$app->params['pdf']['marginBottom'],
            'methods' => [
                'SetHeader' => ['<img src=\'' . $pdelogo . '\'>'],
                'SetFooter' => ['<p style="text-align: center; border-top: 1px solid #ccc;">Σελίδα {PAGENO} από {nb}<br><img src=\'' . $actionlogo . '\'></p>'], // leave it as failsafe, but it will be altered in view
            ]
        ]);

        return $pdf->render();
    }

    public function actionPrintDenials($applicantId = null)
    {
        ini_set("pcre.backtrack_limit", "5000000");

        $output_filename = 'ΔΗΛΩΣΗ-ΑΡΝΗΣΗΣ-ΤΟΠΟΘΕΤΗΣΗΣ.pdf';
        if (isset($applicantId) && is_numeric($applicantId) && intval($applicantId) > 0) {
            $users = [Applicant::findOne(['id' => $applicantId])];
            $output_filename = sprintf("ΔΗΛΩΣΗ-ΑΡΝΗΣΗΣ-ΤΟΠΟΘΕΤΗΣΗΣ-%s.pdf", $users[0]->getFilenameLabel());
        } else {
            $users = Applicant::find()->where(['state' => 1])->all();
            $output_filename = 'ΔΗΛΩΣΗ-ΑΡΝΗΣΗΣ-ΤΟΠΟΘΕΤΗΣΗΣ-ΟΛΕΣ.pdf';
        }
        if (count($users) == 0) {
            Yii::$app->session->addFlash('info', "Δεν εντοπίστηκε καμία/κανένας αιτούσα/αιτών και καμία ενεργή αίτηση.");
            return $this->redirect(['admin/view-denials']);
        }

        $data = [];
        for ($j = 0; $j < count($users); $j++) {
            $data[$j]['user'] = $users[$j];
        }


        $actionlogo = "file:///" . realpath(Yii::getAlias('@images/logo.jpg'));
        $pdelogo = "file:///" . realpath(Yii::getAlias('@images/pdelogo.jpg'));
        $content = $this->renderPartial('/application/print-denial', [
            'data' => $data,
            'info_content' => \app\models\Page::getPageContent('info_denial')
        ]);

        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'filename' => $output_filename,
            'destination' => Pdf::DEST_DOWNLOAD,
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:16px}',
            'options' => [
                'title' => 'Περιφερειακή Διεύθυνση Πρωτοβάθμιας και Δευτεροβάθμιας Εκπαίδευσης Κρήτης',
                'defaultheaderline' => 0,
                'defaultfooterline' => 0
            ],
            'marginTop' => Yii::$app->params['pdf']['marginTop'],
            'marginBottom' => Yii::$app->params['pdf']['marginBottom'],
            'methods' => [
                'SetHeader' => ['<img src=\'' . $pdelogo . '\'>'],
                'SetFooter' => ['<p style="text-align: center; border-top: 1px solid #ccc;">Σελίδα {PAGENO} από {nb}<br><img src=\'' . $actionlogo . '\'></p>'],
            ]
        ]);

        return $pdf->render();
    }
}
