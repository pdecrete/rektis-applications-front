<?php 

namespace app\controllers;

use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\web\ForbiddenHttpException;
use yii\web\ServerErrorHttpException;

/**
 * BridgeController handles api functionality.
 *
 * In general the response of the actions is intended to be received as
 * application/json ot application/xml formatted data. The relevant
 * request header (Accept) is used to set the response type.
 *
 * The http status code denotes the request status. Anything other than 200
 * should be considered an error status.
 * Upon error, the [message] response parameter should contain a human
 * readable message.
 * When the http status code is 200 AND the [success] response parameter
 * is true, the call should be considered as succesfully completed.
 * Upon succes, the [message] response parameter could be empty or null or
 * contain a human readable message.
 *
 * @throws \yii\web\ForbiddenHttpException
 * @throws \yii\web\ServerErrorHttpException
 */
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
                    /**
                     * only allow access when [enable_data_load] is on, thus when
                     * [enable_data_load] is off, this forbids execution of actions
                     */
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
                    /**
                     * only allow access when [enable_data_unload] is on, thus when
                     * [enable_data_unload] is off, this forbids execution of actions
                     */
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
                    // do not rely on this one only
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
            'unload' => ['POST'],
            'clear' => ['DELETE']
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
     * Prior to loading new data, clearing of data is not performed implicitly,
     * so a clear of old data should have been performed explicitly via a [clear] call.
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
            'success' => true,
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
        if (($status = \Yii::$app->adminHelper->clearData()) === true) {
            return [
                'success' => true,
                'message' => null
            ];
        } else {
            throw new ServerErrorHttpException($status);
        }
    }
}
