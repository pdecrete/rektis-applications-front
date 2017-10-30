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
use yii\web\ForbiddenHttpException;
use yii\web\GoneHttpException;
use kartik\mpdf\Pdf;
use app\models\AuditLog;
use app\components\TermsAgreement;

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
            [
            'class' => TermsAgreement::className(),
            'except' => ['request-agree'],
            ],
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
                        'actions' => ['apply', 'request-deny', 'deny', 'request-agree', 'terms-agree', 'print-denial'], // 'delete-my-application', 'my-delete'],
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
        Yii::trace('Applications raw list display');

        $searchModel = new ApplicationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     *
     * @param int $printMode
     * @return mixed
     */
    public function actionMyApplication($printMode = 0)
    {
        $user = Applicant::findOne(['vat' => \Yii::$app->user->getIdentity()->vat, 'specialty' => \Yii::$app->user->getIdentity()->specialty]);

        if ($user->state == Applicant::DENIED_TO_APPLY) {
            return $this->redirect(['site/index']);
        }

        $choices = $user->applications;

        // if no application exists, forward to create
        if (count($choices) == 0) {
            Yii::trace('User requested not existant application; forwarding to apply');
            Yii::$app->session->addFlash('info', "Δεν υπάρχει αποθηκευμένη αίτηση. Μπορείτε να υποβάλλετε νέα αίτηση.");
            return $this->redirect(['apply']);
        }

        $choicesArray = \yii\helpers\ArrayHelper::toArray($choices);

        for ($i = 0; $i < count($choicesArray); $i++) {
            $choiceActRec = Choice::findOne(['id' => $choicesArray[$i]['choice_id']]);
            $prefectureId = $choiceActRec->prefecture_id;
            $choicesArray[$i]['Position'] = $choiceActRec->position;
            $choicesArray[$i]['PrefectureName'] = Prefecture::findOne(['id' => $prefectureId])->prefecture;
            $choicesArray[$i]['RegionName'] = Prefecture::findOne(['id' => $prefectureId])->region;
        }

        $provider = new \yii\data\ArrayDataProvider([
            'allModels' => $choicesArray
        ]);

        // submit ts
        $user_id = Yii::$app->has('user', true) ? Yii::$app->get('user')->getId() : null;
        $last_submit_model = AuditLog::find()->withUserId($user_id)->applicationSubmits()->one();

        if ($printMode == 1) {
            $data[0]['user'] = $user;
            $data[0]['provider'] = $provider;
            $data[0]['last_submit_model'] = $last_submit_model;
            $content = $this->renderPartial('print', [
                'data' => $data,
            ]);

            $actionlogo = "file:///" . realpath(dirname(__FILE__) . '/../web/images/logo.jpg');
            $pdelogo = "file:///" . realpath(dirname(__FILE__) . '/../web/images/pdelogo.jpg');
            // setup kartik\mpdf\Pdf component
            $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8,
                'format' => Pdf::FORMAT_A4,
                'orientation' => Pdf::ORIENT_PORTRAIT,
                'filename' => 'aitisi.pdf',
                'destination' => Pdf::DEST_DOWNLOAD,
                'content' => $content,
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => '.kv-heading-1{font-size:18px}',
                'options' => ['title' => 'Περιφερειακή Διεύθυνση Πρωτοβάθμιας και Δευτεροβάθμιας Εκπαίδευσης Κρήτης'],
                'methods' => [
                    'SetHeader' => ['<img src=\'' . $pdelogo . '\'>'],
                    'SetFooter' => ['<img src=\'' . $actionlogo . '\'>Σελίδα: {PAGENO} από {nb}'],
                ]
            ]);
            Yii::info('Generate PDF file for application', 'user.application');

            return $pdf->render();
        } else {
            Yii::trace('Display application', 'user.application');

            return $this->render('view', [
                    'user' => $user,
                    'dataProvider' => $provider,
                    'enable_applications' => (\app\models\Config::getConfig('enable_applications') === 1),
                    'last_submit_model' => $last_submit_model
            ]);
        }
    }

    /**
     * Creates a new Application model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionApply()
    {
        $user = Applicant::findOne(['vat' => \Yii::$app->user->getIdentity()->vat, 'specialty' => \Yii::$app->user->getIdentity()->specialty]);
        if ($user->state == Applicant::DENIED_TO_APPLY) {
            return $this->redirect(['site/index']);
        }
        $prefectrs_prefrnc_model = PrefecturesPreference::find()->where(['applicant_id' => $user->id])->orderBy('order')->all();
        if (count($prefectrs_prefrnc_model) == 0) {
            Yii::$app->session->addFlash('info', "Δεν υπάρχουν νομοί προτιμήσης.");
            return $this->redirect(['site/index']);
        }

        $prefectrs_choices_model = Choice::classname();
        $models = [];
        $prefectures_choices = [];
        $counter = 1;

        if ($user->applications) { // Edit, if the user has already applied
            foreach ($prefectrs_prefrnc_model as $preference) {
                $userChoices = $user->getApplications()->all();
                $prefectures_choices[$preference->getPrefectureName()] = $preference->prefect_id;
                foreach ($userChoices as $userChoice) {
                    $choice = Choice::findOne($userChoice->choice_id);
                    if ($choice->prefecture_id === $preference->prefect_id) {
                        $models[$preference->getPrefectureName()][$counter] = $userChoice;
                        $counter++;
                    }
                }
            }
        } else { // Make new application, if the user has not already applied
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
                    Yii::$app->session->addFlash('success', "Η αίτησή σας έχει υποβληθεί.");

                    Yii::info('User application submitted', 'user.application.submit');
                    return $this->redirect(['my-application']);
                } catch (\Exception $e) {
                    Yii::$app->session->addFlash('danger', "Προέκυψε σφάλμα κατά την αποθήκευση των επιλογών σας. Παρακαλώ προσπαθήστε ξανά.");
                    Yii::error('User application failure', 'user.application.submit');
                    $transaction->rollBack();
                }
            } else {
                Yii::error('User application failure', 'user.application.submit');
                Yii::$app->session->addFlash('danger', "Παρακαλώ διορθώστε τα λάθη που υπάρχουν στις επιλογές και δοκιμάστε ξανά.");
            }
        }

        Yii::trace('Display application form', 'user.application');
        return $this->render('apply', [
                'models' => $models,
                'user' => $user,
                'prefectures_choices' => $prefectures_choices,
        ]);
    }

    public function actionDeleteMyApplication()
    {
        throw new GoneHttpException();
        /*
          $user = Applicant::findOne(['vat' => \Yii::$app->user->getIdentity()->vat, 'specialty' => \Yii::$app->user->getIdentity()->specialty]);
          // if user has made no choices, forward to index
          if (count($user->applications) == 0) {
          Yii::$app->session->addFlash('info', "Δεν υπάρχει αποθηκευμένη αίτηση");
          return $this->redirect(['site/index']);
          }

          return $this->render('delete_my_application');
         */
    }

    public function actionMyDelete()
    {
        throw new GoneHttpException();
        /*
          $user = Applicant::findOne(['vat' => \Yii::$app->user->getIdentity()->vat, 'specialty' => \Yii::$app->user->getIdentity()->specialty]);
          Application::updateAll(['deleted' => 1], ['applicant_id' => $user->id]);

          Yii::$app->session->addFlash('info', "Η αίτηση έχει διαγραφεί");
          return $this->redirect(['site/index']);
         */
    }

    /**
     * Deletes an existing Application model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        throw new GoneHttpException();
        /*$this->findModel($id)->delete();
        return $this->redirect(['my-application']);*/
    }

    public function actionRequestDeny()
    {
        Yii::trace('User request deny', 'user.deny');

        $user = Applicant::findOne(\Yii::$app->user->getIdentity()->id);
        if (count($user->applications) > 0) {
            throw new ForbiddenHttpException();
        }
        return $this->render('confirm-deny-application');
    }

    public function actionDeny()
    {
        $user = Applicant::findOne(\Yii::$app->user->getIdentity()->id);
        if (count($user->applications) > 0) {
            throw new ForbiddenHttpException();
        }
        $user->setAttribute('state', 1);
        try {
            $rowsAffected = $user->updateAll(['state' => 1], ['id' => $user->id]);
            if ($rowsAffected != 1) {
                throw new \Exception();
            }
            Yii::$app->session->addFlash('info', "Η δήλωση άρνησης αίτησης έχει καταχωριστεί.");
            Yii::info('User deny application', 'user.deny');
        } catch (\Exception $nse) {
            Yii::$app->session->addFlash('danger', "Προέκυψε σφάλμα κατά την αποθήκευση της επιλογής σας. Παρακαλώ προσπαθήστε ξανά.");
            Yii::error('User deny application error', 'user.deny');
        }
        return $this->redirect(['site/index']);
    }

    public function actionPrintDenial()
    {
        $user = Applicant::findOne(\Yii::$app->user->getIdentity()->id);
        if ($user->state != 1 || count($user->applications) > 0) {
            throw new ForbiddenHttpException();
        }
        $data[0]['user'] = $user;
        $content = $this->renderPartial('print-denial', ['data' => $data]);
        $actionlogo = "file:///" . realpath(dirname(__FILE__) . '/../web/images/logo.jpg');
        $pdelogo = "file:///" . realpath(dirname(__FILE__) . '/../web/images/pdelogo.jpg');

        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'filename' => 'arnisiaitisis.pdf',
            'destination' => Pdf::DEST_DOWNLOAD,
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'options' => ['title' => 'Περιφερειακή Διεύθυνση Πρωτοβάθμιας και Δευτεροβάθμιας Εκπαίδευσης Κρήτης'],
            'methods' => [
                'SetHeader' => ['<img src=\'' . $pdelogo . '\'>'],
                'SetFooter' => ['<img src=\'' . $actionlogo . '\'>Σελίδα: {PAGENO} από {nb}'],
            ]
        ]);
        Yii::info('Generate PDF file for application', 'user.application');

        return $pdf->render();
    }

    public function actionRequestAgree()
    {
        Yii::trace('User request agree', 'user.agree');

        $user = Applicant::findOne(\Yii::$app->user->getIdentity()->id);
        if (count($user->applications) > 0) {
            throw new ForbiddenHttpException();
        }
        if ($user->agreedterms != null) {
            \Yii::$app->response->redirect(['site/index']);
        }
        return $this->render('show-terms');
    }

    public function actionTermsAgree()
    {
        Yii::trace('User request agree', 'user.agree');

        $user = Applicant::findOne(\Yii::$app->user->getIdentity()->id);
        if (count($user->applications) > 0) {
            throw new ForbiddenHttpException();
        }
        try {
            $rowsAffected = $user->updateAll(['agreedterms' => time()], ['id' => $user->id]);
            if ($rowsAffected != 1) {
                throw new \Exception();
            }
            Yii::$app->session->addFlash('info', "Έχετε αποδεχτεί τους όρους. Μπορείτε να συνεχίσετε στην υποβολή των προτιμήσεών σας.");
            Yii::info('User agree terms', 'user.agree');
        } catch (\Exception $nse) {
            Yii::$app->session->addFlash('danger', "Προέκυψε σφάλμα κατά την αποθήκευση της επιλογής σας. Παρακαλώ προσπαθήστε ξανά.");
            Yii::error('User agree terms error', 'user.agree');
        }
        return $this->redirect(['site/index']);
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
