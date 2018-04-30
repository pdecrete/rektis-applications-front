<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\Config;

class EnableController extends \yii\web\Controller
{
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'enable-data-load' => ['POST'],
                    'disable-data-load' => ['POST'],
                    'enable-data-unload' => ['POST'],
                    'disable-data-unload' => ['POST'],
                ],
            ],
        ];
    }

    public function actionConfirmEnable()
    {
        return $this->render('confirm-enable', [
                'enable_applications' => (Config::getConfig('enable_applications') === 1)
        ]);
    }

    /**
     * Enable loading application data.
     */
    public function actionEnableDataLoad()
    {
        $config = $this->findConfig('enable_data_load', 'Διαλειτουργικότητα - φόρτωση στοιχείων');

        $config->value = '1';
        if ($config->save()) {
            Yii::trace('Data load enabled successfully', 'admin');
            Yii::$app->session->addFlash('info', "Ενεργοποιήθηκε η δυνατότητα φόρτωσης στοιχείων μέσω διαλειτουργικότητας.");
        } else {
            Yii::trace('Data load not enabled due to error', 'admin');
            Yii::$app->session->addFlash('danger', "Δεν ενεργοποιήθηκε η δυνατότητα φόρτωσης στοιχείων μέσω διαλειτουργικότητας.");
        }
        return $this->redirect(['site/index']);
    }

    /**
     * Disable loading application data.
     */
    public function actionDisableDataLoad()
    {
        $config = $this->findConfig('enable_data_load', 'Διαλειτουργικότητα - φόρτωση στοιχείων');

        $config->value = '0';
        if ($config->save()) {
            Yii::trace('Data load enabled successfully', 'admin');
            Yii::$app->session->addFlash('info', "Απενεργοποιήθηκε η δυνατότητα φόρτωσης στοιχείων μέσω διαλειτουργικότητας.");
        } else {
            Yii::trace('Data load not enabled due to error', 'admin');
            Yii::$app->session->addFlash('danger', "Δεν απενεργοποιήθηκε η δυνατότητα φόρτωσης στοιχείων μέσω διαλειτουργικότητας.");
        }
        return $this->redirect(['site/index']);
    }

    /**
     * Enable unloading application data.
     */
    public function actionEnableDataUnload()
    {
        $config = $this->findConfig('enable_data_unload', 'Διαλειτουργικότητα - κατέβασμα στοιχείων');

        $config->value = '1';
        if ($config->save()) {
            Yii::trace('Data load enabled successfully', 'admin');
            Yii::$app->session->addFlash('info', "Ενεργοποιήθηκε η δυνατότητα μεταφόρτωσης στοιχείων μέσω διαλειτουργικότητας.");
        } else {
            Yii::trace('Data load not enabled due to error', 'admin');
            Yii::$app->session->addFlash('danger', "Δεν ενεργοποιήθηκε η δυνατότητα μεταφόρτωσης στοιχείων μέσω διαλειτουργικότητας.");
        }
        return $this->redirect(['site/index']);
    }

    /**
     * Disable unloading application data.
     */
    public function actionDisableDataUnload()
    {
        $config = $this->findConfig('enable_data_unload', 'Διαλειτουργικότητα - κατέβασμα στοιχείων');

        $config->value = '0';
        if ($config->save()) {
            Yii::trace('Data load enabled successfully', 'admin');
            Yii::$app->session->addFlash('info', "Απενεργοποιήθηκε η δυνατότητα μεταφόρτωσης στοιχείων μέσω διαλειτουργικότητας.");
        } else {
            Yii::trace('Data load not enabled due to error', 'admin');
            Yii::$app->session->addFlash('danger', "Δεν απενεργοποιήθηκε η δυνατότητα μεταφόρτωσης στοιχείων μέσω διαλειτουργικότητας.");
        }
        return $this->redirect(['site/index']);
    }

    /**
     * Enable applications.
     */
    public function actionEnable()
    {
        $config = $this->findConfig('enable_applications', 'Υποβολή αιτήσεων');

        $config->value = '1';
        if ($config->save()) {
            Yii::trace('Applications enabled successfully', 'admin');
            Yii::$app->session->addFlash('info', "Ενεργοποιήθηκε η δυνατότητα υποβολής αιτήσεων για τους εκπαιδευτικούς.");
        } else {
            Yii::trace('Applications not enabled due to error', 'admin');
            Yii::$app->session->addFlash('danger', "Δεν ενεργοποιήθηκε η δυνατότητα υποβολής αιτήσεων για τους εκπαιδευτικούς.");
        }
        return $this->redirect(['site/index']);
    }

    /**
     * Disable applications.
     */
    public function actionDisable()
    {
        $config = $this->findConfig('enable_applications', 'Υποβολή αιτήσεων');

        $config->value = '0';
        if ($config->save()) {
            Yii::trace('Applications disabled successfully', 'admin');
            Yii::$app->session->addFlash('info', "Απενεργοποιήθηκε η δυνατότητα υποβολής αιτήσεων για τους εκπαιδευτικούς.");
        } else {
            Yii::trace('Applications not disabled due to error', 'admin');
            Yii::$app->session->addFlash('danger', "Δεν απενεργοποιήθηκε η δυνατότητα υποβολής αιτήσεων για τους εκπαιδευτικούς.");
        }
        return $this->redirect(['site/index']);
    }

    /**
     * Locate a config model by name
     * 
     * @param string $name The name of the config parameters 
     * @param string $label The descriptive text of the config parameter
     * @return Config The config model 
     * @throws NotFoundHttpException
     */
    protected function findConfig($name, $label = '')
    {
        $config = Config::findOne(['name' => $name]);
        if ($config === null) {
            throw new NotFoundHttpException('Δεν υπάρχει παράμετρος για ενεργοποίηση ή απενεργοποίηση της δυνατότητας' . (is_string($label) ? ": {$label}" : ''));
        }

        return $config;
    }
}
