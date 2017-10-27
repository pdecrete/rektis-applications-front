<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use app\models\Config;

class EnableApplicationsController extends \yii\web\Controller
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
        ];
    }

    public function actionConfirmEnable()
    {
        return $this->render('confirm-enable', [
                'enable_applications' => (Config::getConfig('enable_applications') === 1)
        ]);
    }

    public function actionEnable()
    {
        $config = Config::findOne(['name' => 'enable_applications']);
        if ($config === null) {
            throw new NotFoundHttpException('Δεν υπάρχει παράμετρος για ενεργοποίηση ή απενεργοποίηση δυνατότητας υποβολής αιτήσεων');
        }

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

    public function actionDisable()
    {
        $config = Config::findOne(['name' => 'enable_applications']);
        if ($config === null) {
            throw new NotFoundHttpException('Δεν υπάρχει παράμετρος για ενεργοποίηση ή απενεργοποίηση δυνατότητας υποβολής αιτήσεων');
        }

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
}
