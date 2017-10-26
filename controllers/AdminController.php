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
                'enable_applications' => (\app\models\Config::getConfig('enable_applications') === 1)
        ]);
    }

    public function actionClearData()
    {
        try {
            \Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();
            \Yii::$app->db->createCommand()->truncateTable('{{%application}}')->execute();
            \Yii::$app->db->createCommand()->truncateTable('{{%choice}}')->execute();
            \Yii::$app->db->createCommand()->truncateTable('{{%applicant}}')->execute();
            \Yii::$app->db->createCommand()->truncateTable('{{%prefecture}}')->execute();
            \Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();

            Yii::$app->session->addFlash('success', "Ολοκληρώθηκε η εκκαθάριση των στοιχείων.");
        } catch (\Exception $e) {
            Yii::$app->session->addFlash('danger', "Προέκυψε σφάλμα κατά την εκκαθάριση των στοιχείων. <strong>Παρακαλώ ελέγξτε τα στοιχεία!</strong>. Το μύνημα λάθους από τη βάση δεδομένων ήταν: " . $e->getMessage());
        }
        return $this->redirect(['index']);
    }

    public function actionOverview()
    {
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
                                        'lastname', 'firstname', 'vat', 'identity', 'specialty']]]);

        return $this->render('view-applications', ['users' => $dataProvider]);
    }


    public function actionViewDenials()
    {
        $dataProvider = new ArrayDataProvider(['allModels' => Applicant::find()->where(['state' => 1])->all(),
                'pagination' => ['pageSize' => 100],
                'sort' => ['attributes' => ['lastname', 'firstname', 'vat', 'identity', 'specialty']]
        ]);
        return $this->render('view-denials', ['users' => $dataProvider,
                'sort' => ['attributes' => ['vat', 'identity', 'specialty']],
        ]);
    }

    public function actionPrintApplications($applicantId = null)
    {
        if (isset($applicantId) && is_numeric($applicantId) && intval($applicantId) > 0) {
            $users = [ Applicant::findOne(['id' => $applicantId]) ];
        } else {
            $users = Applicant::find()->joinWith(['applications' => function (\yii\db\ActiveQuery $query) {
                $query->andWhere(['deleted' => 0])->count() > 0;
            }])->orderBy(['specialty' => SORT_ASC, 'points' => SORT_DESC])->all();
        }

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
                'allModels' => $choicesArray
            ]);


            $data[$j]['user'] = $users[$j];
            $data[$j]['provider'] = $provider;
        }
        $actionlogo = "file:///" . realpath(dirname(__FILE__). '/../web/images/logo.jpg');
        $pdelogo = "file:///" . realpath(dirname(__FILE__). '/../web/images/pdelogo.jpg');
        $content = $this->renderPartial('../application/print', ['data' => $data]);
        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'filename' => 'aitisi.pdf',
            'destination' => Pdf::DEST_DOWNLOAD,
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'options' => ['title' => 'Περιφερειακή Διεύθυνση Πρωτοβάθμιας και Δευτεροβάθμιας Εκπαίδευσης Κρήτης'],
            'methods' => [
                      'SetHeader' => ['<img src=\'' . $pdelogo . '\'>'],
                      'SetFooter' => ['<img src=\'' . $actionlogo . '\'>Σελίδα: {PAGENO} από {nb}'],
            ]
        ]);

        return $pdf->render();
    }
}
