<?php 

namespace app\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\filters\auth\HttpBearerAuth;
use yii\web\ForbiddenHttpException;

class BridgeController extends \yii\rest\Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'actions' => ['load', 'clear'],
                    'allow' => false,
                    'matchCallback' => function ($rule, $action) {
                        return (\app\models\Config::getConfig('enable_data_load') === 0);
                    },
                    'denyCallback' => function ($rule, $action) {
                        throw new ForbiddenHttpException('Data loading is not enabled.');
                    }
                ],
                [
                    'actions' => ['unload'],
                    'allow' => false,
                    'matchCallback' => function ($rule, $action) {
                        return (\app\models\Config::getConfig('enable_data_unload') === 0);
                    },
                    'denyCallback' => function ($rule, $action) {
                        throw new ForbiddenHttpException('Data unloading is not enabled.');
                    }
                ],
                [
                    'allow' => true,
                    'ips' => \Yii::$app->params['bridge-allowed-ips']
                ],
            ],
        ];
        return $behaviors;
    }

    protected function verbs()
    {
        return [
            'index'  => ['GET', 'POST'],
            'load' => ['POST'],
            'unload' => ['GET', 'POST'],
            'clear' => ['DELETE'],
        ];
    }

    public function actionIndex()
    {
        return [
            'status' => true,
            'message' => null
        ];
    }

    /**
     * Load action is used to bring new data into the application.
     * The new data consists of: positions, applicants and applicant preferences.
     * Prior to loading new data, a clear of old data is performed.
     * 
     */
    public function actionLoad()
    {
        return [
            'status' => true,
            'message' => 'Load action TEST'
        ];
        // check for access 

        // preliminary check of posted data 

        // start be verifying that all previous data is cleared

        // do the actual import 

    }

    /**
     * Unload action is used to get the applicant and application data.
     * This includes applications, denials and any other information 
     * gathered by the frontend app. 
     * 
     */
    public function actionUnload()
    {
        return [
            'status' => true,
            'message' => 'Unload action TEST'
        ];

        // check for access 

        // gather data and package 

        // send

    }

    /**
     * Action clear is used to delete applicant information.
     * 
     */
    public function actionClear()
    {
        return [
            'status' => true,
            'message' => null
        ];
    }


}
