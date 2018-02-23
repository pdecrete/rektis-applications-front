<?php 

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\web\ForbiddenHttpException;
use yii\web\ServerErrorHttpException;
use app\models\AuditLog;
use app\models\Application;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use app\models\Prefecture;
use yii\db\IntegrityException;
use app\models\Applicant;
use app\models\Choice;
use app\models\PrefecturesPreference;

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
     * @throws yii\web\BadRequestHttpException If data is not valid 
     */
    public function actionLoad()
    {
        $transaction = \Yii::$app->db->beginTransaction(); // perform alla data loading or rollback on error
        // expect to get a JSON data feed (framework parser should not be enabled)
        try {
            $data = Json::decode(\Yii::$app->request->getRawBody());
            $data_groups = array_keys($data);
            $expected_data_groups = ['prefectures', 'teachers', 'positions', 'placement_preferences'];
            $has_all_expected_data_groups = empty(array_diff($expected_data_groups, $data_groups));
            if ($has_all_expected_data_groups === false) {
                throw new \Exception('Missing required data: ' . implode(', ', $expected_data_groups));
            }

            // data seems ok, process
            // save prefectures 
            $prefectures_load_data = array_map(function ($prefecture) {
                return [
                    $prefecture['index'], $prefecture['region'], $prefecture['prefecture'], $prefecture['ref']
                ];
            }, $data['prefectures']);
            $prefectures_load_data_inserted = Yii::$app->db->createCommand()
                ->batchInsert(Prefecture::tableName(), ['id', 'region', 'prefecture', 'reference'], $prefectures_load_data)
                ->execute();

            // save teachers 
            $teachers_load_data = array_map(function ($teacher) {
                return [
                    $teacher['index'], $teacher['vat'], $teacher['identity'], $teacher['specialty'], $teacher['ref']
                ];
            }, $data['teachers']);
            $teachers_load_data_inserted = Yii::$app->db->createCommand()
                ->batchInsert(Applicant::tableName(), ['id', 'vat', 'identity', 'specialty', 'reference'], $teachers_load_data)
                ->execute(); // state, points, agreedterms, statets rely on database defaults.

            // save positions 
            $positions_load_data = array_map(function ($position) {
                return [
                    $position['index'], $position['specialty'], $position['label'], $position['school_type'], $position['prefecture'], $position['ref'], 1
                ];
            }, $data['positions']);
            $positions_load_data_inserted = Yii::$app->db->createCommand()
                ->batchInsert(Choice::tableName(), ['id', 'specialty', 'position', 'school_type', 'prefecture_id', 'reference', 'count'], $positions_load_data)
                ->execute(); 

            // save placement preferences 
            $preference_load_data = array_map(function ($preference) {
                return [
                    $preference['prefecture'], $preference['teacher'], $preference['school_type'], $preference['order']
                ];
            }, $data['placement_preferences']);
            $preference_load_data_inserted = Yii::$app->db->createCommand()
                ->batchInsert(PrefecturesPreference::tableName(), ['prefect_id', 'applicant_id', 'school_type', 'order'], $preference_load_data)
                ->execute(); // id left auto increment

        } catch (IntegrityException $x) {
            $transaction->rollBack();
            throw new BadRequestHttpException('Data feed may containt existing data; db error code: ' . $x->getCode());
        } catch (\Exception $x) {
            $transaction->rollBack();
            throw new BadRequestHttpException('Cannot process data feed; ' . $x->getMessage());
        }
        $transaction->commit();

        return [
            'status' => true,
            'message' => "Load completed ({$prefectures_load_data_inserted} prefectures, {$positions_load_data_inserted} positions, {$teachers_load_data_inserted} applicants, {$preference_load_data_inserted} placement preferences)",
        ];
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
            'message' => 'Unload action TEST',
            'data' => [
                'audit_log' => AuditLog::find()->all(),
                'applications' => Application::find()->all(),
                // TODO check data that is returned 
            ]
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
