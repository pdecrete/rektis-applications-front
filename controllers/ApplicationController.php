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
use app\models\Prefecture;
use app\models\PrefecturesPreference;
use app\models\Choice;
use kartik\mpdf\Pdf;


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
                        'actions' => ['my-application'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return false === \Yii::$app->user->identity->isAdmin();
                        }
                    ],
                    [
                        'actions' => ['apply', 'delete-my-application', 'my-delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return (false === \Yii::$app->user->identity->isAdmin()) &&
                                (1 === \app\models\Config::getConfig('enable_applications'));
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
    public function actionMyApplication($printMode = 0)
    {     
        $user = Applicant::findOne(['vat' => \Yii::$app->user->getIdentity()->vat, 'specialty' => \Yii::$app->user->getIdentity()->specialty]);
        $choices = $user->applications;
        //$prefectrs_prefrnc_model = PrefecturesPreference::find()->where(['applicant_id' => $user->id])->orderBy('order')->all();
        //$prefectrs_choices_model = Choice::classname();
        
        // if no application exists, forward to create
        if (count($choices) == 0) {
            Yii::$app->session->addFlash('info', "Δεν υπάρχει αποθηκευμένη αίτηση. Μπορείτε να υποβάλλετε νέα αίτηση.");
            return $this->redirect(['apply']);
        }
        
        $choicesArray = \yii\helpers\ArrayHelper::toArray($choices);
        
		for($i = 0; $i < count($choicesArray); $i++){
		   $choiceActRec = Choice::findOne(['id' => $choicesArray[$i]['choice_id']]);
		   $prefectureId = $choiceActRec->prefecture_id;
		   $choicesArray[$i]['Position'] = $choiceActRec->position;
		   $choicesArray[$i]['PrefectureName'] = Prefecture::findOne(['id' => $prefectureId])->prefecture;
		   $choicesArray[$i]['RegionName'] = Prefecture::findOne(['id' => $prefectureId])->region;
	    }
        //echo "<pre>"; print_r($choicesArray); echo "</pre>"; die();

        $provider = new \yii\data\ArrayDataProvider([
            'allModels' => $choicesArray,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);
    
        if($printMode == 1){
			$content = $this->renderPartial('print', [
                'user' => $user,
                'dataProvider' => $provider,
                'enable_applications' => (\app\models\Config::getConfig('enable_applications') === 1)
            ]);
            
            // setup kartik\mpdf\Pdf component
            $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8, 
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            'filename' => 'test.pdf',
            'destination' => Pdf::DEST_DOWNLOAD,
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'options' => ['title' => 'Περιφερειακή Διεύθυνση Πρωτοβάθμιας και Δευτεροβάθμιας Εκπαίδευσης Κρήτης'],
            'methods' => [ 
                'SetHeader'=>['Περιφερειακή Διεύθυνση Πρωτοβάθμιας και Δευτεροβάθμιας Εκπαίδευσης Κρήτης'], 
                'SetFooter'=>['Σελίδα: {PAGENO}'],
                ]
            ]);
    
            // return the pdf output as per the destination setting
            return $pdf->render();
	    }
        
        return $this->render('view', [
                'user' => $user,
                'dataProvider' => $provider,
                'enable_applications' => (\app\models\Config::getConfig('enable_applications') === 1)
        ]);
    }


    /**
     * Creates a new Application model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionApply()
    {		
        $user = Applicant::findOne(['vat' => \Yii::$app->user->getIdentity()->vat, 'specialty' => \Yii::$app->user->getIdentity()->specialty]);
		$prefectrs_prefrnc_model = PrefecturesPreference::find()->where(['applicant_id' => $user->id])->orderBy('order')->all();
		if (count($prefectrs_prefrnc_model) == 0) {
            Yii::$app->session->addFlash('info', "Δεν υπάρχουν νομοί προτιμήσης.");
            return $this->redirect(['site/index']);
        }
        
		$prefectrs_choices_model = Choice::classname();	
        $models = [];
        $prefectures_choices = [];
        $counter = 1;			
        
        if($user->applications){
			foreach ($prefectrs_prefrnc_model as $preference) {
			    $userChoices = $user->getApplications()->all();
                $prefectures_choices[$preference->getPrefectureName()] = $preference->prefect_id;
                foreach ($userChoices as $userChoice) {
					$choice = Choice::findOne($userChoice->choice_id);
					if($choice->prefecture_id ===  $preference->prefect_id){
                        $models[$preference->getPrefectureName()][$counter] = $userChoice;
                        $counter++;
				    }
                }
            }
		}
		else{
            foreach ($prefectrs_prefrnc_model as $preference) {
                $choices = Choice::getChoices($preference->prefect_id, $user->specialty);
                $prefectures_choices[$preference->getPrefectureName()] = $preference->prefect_id;
                foreach ($choices as $choice) {
                    $models[$preference->getPrefectureName()][$counter] = new Application();
                    $helper = $models[$preference->getPrefectureName()][$counter];
                    $helper->applicant_id = $user->id;
                    $helper->deleted = 0;
                    $counter++;
                }
            }
        }

        if (\Yii::$app->request->isPost) {
            foreach (array_keys($models) as $pr) {
                Model::loadMultiple($models[$pr], Yii::$app->request->post());
            }

            $order_counter = 1;
            foreach (array_keys($models) as $pr) {
                foreach ($models[$pr] as $choice) {
                    $choice->order = $order_counter++;
                }
            }
            $models_cnt = $order_counter - 1;

            $valid = true;
            foreach (array_keys($models) as $pr) {
                if (!Model::validateMultiple($models[$pr])) {
                    $valid = false;
                }
            }

            // check unique choices
            $unique_choices_cnt = 0;
            foreach (array_keys($models) as $pr) {
                $choices = array_map(function ($m) {
                    return $m->choice_id;
                }, $models[$pr]);
                $unique_choices_cnt += count(array_unique($choices));
            }

            if ($unique_choices_cnt != $models_cnt) {
                $valid = false;
                Yii::$app->session->addFlash('danger', "Η κάθε επιλογή μπορεί να γίνει μόνο μία φορά.");
            }

            if ($valid) {
                // save all or none
                $transaction = \Yii::$app->db->beginTransaction();

                try {
                    foreach (array_keys($models) as $pr) {
                        foreach ($models[$pr] as $index => $app) {
                            if ($app->save() === false) {
                                throw new \Exception();
                            }
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
                'user' => $user,
                'prefectures_choices' => $prefectures_choices,
        ]);
    }

    public function actionDeleteMyApplication()
    {
        $user = Applicant::findOne(['vat' => \Yii::$app->user->getIdentity()->vat, 'specialty' => \Yii::$app->user->getIdentity()->specialty]);
        // if user has made no choices, forward to index
        if (count($user->applications) == 0) {
            Yii::$app->session->addFlash('info', "Δεν υπάρχει αποθηκευμένη αίτηση");
            return $this->redirect(['site/index']);
        }

        return $this->render('delete_my_application');
    }

    public function actionMyDelete()
    {
        $user = Applicant::findOne(['vat' => \Yii::$app->user->getIdentity()->vat, 'specialty' => \Yii::$app->user->getIdentity()->specialty]);
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
