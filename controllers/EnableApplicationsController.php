<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;

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
        return $this->render('confirm-enable');
    }

    public function actionEnable()
    {
		\Yii::$app->db->createCommand('UPDATE config SET enable_applications = 1 WHERE id=1');
		Yii::$app->session->addFlash('info', "Ενεργοποιήθηκε η δυνατότητα υποβολής αιτήσεων για τους εκπαιδευτικούς.");
        return $this->redirect(['site/index']);
    }
    
   public function actionDisable()
    {
		\Yii::$app->db->createCommand('UPDATE config SET enable_applications = 1 WHERE id=1');
		Yii::$app->session->addFlash('info', "Απενεργοποιήθηκε η δυνατότητα υποβολής αιτήσεων για τους εκπαιδευτικούς.");
        return $this->redirect(['site/index']);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

}
