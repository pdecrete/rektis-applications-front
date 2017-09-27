<?php
namespace app\controllers;

use Yii;
use app\models\Application;
use app\models\ApplicationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Applicant;
use app\models\Model;

/**
 * ApplicationController implements the CRUD actions for Application model.
 */
class ApplicationController extends Controller
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
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['my-application', 'apply', 'delete-my-application', 'my-delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return false === \Yii::$app->user->identity->isAdmin();
                        }
                    ],
                    [
                        'actions' => ['index', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return true === \Yii::$app->user->identity->isAdmin();
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Application models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ApplicationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Application model.
     * @param integer $id
     * @return mixed
     */
    public function actionMyApplication()
    {
        $user = Applicant::findOne(['vat' => \Yii::$app->user->getIdentity()->username]);
        $choices = $user->applications;
        // if no application exists, forward to create
        if (count($choices) == 0) {
            Yii::$app->session->addFlash('info', "Δεν υπάρχει αποθηκευμένη αίτηση. Μπορείτε να υποβάλλετε νέα αίτηση.");
            return $this->redirect(['apply']);
        }

        $provider = new \yii\data\ArrayDataProvider([
            'allModels' => $choices,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);
        return $this->render('view', [
                'user' => $user,
                'dataProvider' => $provider
        ]);
    }

    /**
     * Creates a new Application model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionApply()
    {
        $user = Applicant::findOne(['vat' => \Yii::$app->user->getIdentity()->username]);

        // one application per user only; forward to delete confirmation page
        if ($user->applications) {
            Yii::$app->session->addFlash('warning', "Μόνο μία αίτηση μπορεί να καταχωρηθεί. Φαίνεται πως έχετε ήδη καταχωρήσει αίτηση. <strong>Εάν θέλετε να καταχωρήσετε νέα, πρέπει πρώτα να διαγράψετε την ήδη καταχωρημένη αίτηση.</strong>");
            return $this->redirect(['delete-my-application']);
        }

        $models = [new Application()];

        if (\Yii::$app->request->isPost) {
            $models = Model::createMultiple(Application::classname());

            Model::loadMultiple($models, Yii::$app->request->post());
            array_walk($models, function ($m, $k) use ($user) {
                $m->applicant_id = $user->id;
                $m->deleted = 0;
            });

            $valid = Model::validateMultiple($models);

            $models_cnt = count($models);
            // check max number of applications 
            if ($models_cnt > ($max_cnt = \Yii::$app->params['max-application-items'])) {
                $valid = false;
                $m = reset($models);
                $m->addError('choice_id', "Το μέγιστο πλήθος επιλογών είναι {$max_cnt}");
            }

            // check unique ordering 
            $ordering = array_map(function ($m) {
                return $m->order;
            }, $models);
            if (count(array_unique($ordering)) != $models_cnt) {
                $valid = false;
                $m = reset($models);
                $m->addError('order', "Το πεδίο σειράς επιλογής πρέπει να είναι μοναδικό");
            }
            if (max($ordering) != $models_cnt) {
                $valid = false;
                $m = reset($models);
                $m->addError('order', "Η σειρά επιλογής πρέπει να ξεκινά από τον αριθμό 1 και να αυξάνεται κατά ένα για κάθε επόμενη επιλογή");
            }

            // check unique choices 
            $choices = array_map(function ($m) {
                return $m->choice_id;
            }, $models);
            if (count(array_unique($choices)) != $models_cnt) {
                $valid = false;
                $m = reset($models);
                $m->addError('choice_id', "Η κάθε επιλογή μπορεί να γίνει μόνο μία φορά");
            }

            if ($valid) {
                // save all or none
                $transaction = \Yii::$app->db->beginTransaction();

                try {
                    foreach ($models as $idx => $m) {
                        if ($m->save() === false) {
                            throw new \Exception();
                        }
                    }

                    $transaction->commit();
                    Yii::$app->session->addFlash('success', "Οι επιλογές σας έχουν αποθηκευτεί.");
                    return $this->redirect(['my-application']);
                } catch (\Exception $e) {
                    Yii::$app->session->addFlash('danger', "Προέκυψε σφάλμα κατά την αποθήκευση των επιλογών σας. Παρακαλώ προσπαθήστε ξανά.");
                    $transaction->rollBack();
                }
            } else {
                Yii::$app->session->addFlash('danger', "Παρακαλώ διορθώστε τα λάθη που υπάρχουν στις επιλογές και δοκιμάστε ξανά.");
            }
        }
        return $this->render('apply', [
                'models' => $models,
                'user' => $user
        ]);
    }

    public function actionDeleteMyApplication()
    {
        $user = Applicant::findOne(['vat' => \Yii::$app->user->getIdentity()->username]);
        // if user has made no choices, forward to index
        if (count($user->applications) == 0) {
            Yii::$app->session->addFlash('info', "Δεν υπάρχει αποθηκευμένη αίτηση");
            return $this->redirect(['site/index']);
        }

        return $this->render('delete_my_application');
    }

    public function actionMyDelete()
    {
        $user = Applicant::findOne(['vat' => \Yii::$app->user->getIdentity()->username]);
        Application::updateAll(['deleted' => 1], ['applicant_id' => $user->id]);

        Yii::$app->session->addFlash('info', "Η αίτηση έχει διαγραφεί");
        return $this->redirect(['site/index']);
    }

    /**
     * Deletes an existing Application model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['my-application']);
    }

    /**
     * Finds the Application model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Application the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Application::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
