<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\Application;
use app\models\Applicant;
use League\Csv\Writer;

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
        return $this->render('overview', compact(['dataProvider', 'applicants', 'applications']));
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
}
