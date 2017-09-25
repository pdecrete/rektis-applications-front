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
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return false === \Yii::$app->user->identity->isAdmin();
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
    public function actionView($id)
    {
        return $this->render('view', [
                'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Application model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $user = Applicant::findOne(['vat' => \Yii::$app->user->getIdentity()->username]);
        $models = [new Application()];

        if (\Yii::$app->request->isPost) {
            $models = Model::createMultiple(Application::classname());

            Model::loadMultiple($models, Yii::$app->request->post());
            array_walk($models, function ($m, $k) use ($user) {
                $m->applicant_id = $user->id;
                $m->deleted = 0;
            });

            $valid = Model::validateMultiple($models);

            // check unique ordering 
            $ordering = array_map(function ($m) {
                return $m->order;
            }, $models);
            if (count(array_unique($ordering)) != count($ordering)) {
                $valid = false;
                $m = reset($models);
                $m->addError('order', "Το πεδίο σειράς επιλογής πρέπει να είναι μοναδικό");
            }
            
            // check unique choices 
            $choices = array_map(function ($m) {
                return $m->choice_id;
            }, $models);
            if (count(array_unique($choices)) != count($choices)) {
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
                    return $this->redirect(['view', 'id' => 0]);
                } catch (\Exception $e) {
                    Yii::$app->session->addFlash('danger', "Προέκυψε σφάλμα κατά την αποθήκευση των επιλογών σας. Παρακαλώ προσπαθήστε ξανά.");
                    $transaction->rollBack();
                }
            } else {
                Yii::$app->session->addFlash('danger', "Παρακαλώ διορθώστε τα λάθη που υπάρχουν στις επιλογές και δοκιμάστε ξανά.");
            }
        }
        return $this->render('create', [
                'models' => $models,
                'user' => $user
        ]);
    }

    /**
     * Updates an existing Application model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                    'model' => $model,
            ]);
        }
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

        return $this->redirect(['index']);
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
