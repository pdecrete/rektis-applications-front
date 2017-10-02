<?php
namespace app\controllers;

use yii\filters\AccessControl;
use app\models\Application;
use League\Csv\Writer;

class AdminController extends \yii\web\Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
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
        return $this->render('index');
    }

    public function actionOverview()
    {
        throw new \Exception('Η λειτουργία δεν είναι προς το παρόν υλοποιημένη');
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
