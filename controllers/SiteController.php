<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
// use app\models\ContactForm;
use yii\web\GoneHttpException;
use app\models\Applicant;

class SiteController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
//            'captcha' => [
//                'class' => 'yii\captcha\CaptchaAction',
//                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
//            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        Yii::trace('Index page display');
        if (Yii::$app->user->isGuest) {
            return $this->render('index-guest');
        } else {
            if (\Yii::$app->user->identity->isAdmin()) {
                return $this->redirect(['admin/index']);
            } else {
                $user = Applicant::findOne(['vat' => \Yii::$app->user->getIdentity()->vat, 'specialty' => \Yii::$app->user->getIdentity()->specialty]);
                if ($user->state == Applicant::DENIED_TO_APPLY) {
                    return $this->render('denied-application');
                }
                return $this->render('index', [
                        'enable_applications' => (\app\models\Config::getConfig('enable_applications') === 1)
                ]);
            }
        }
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        Yii::trace('Login form display', 'user');
        return $this->render('login', [
                'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        $user_id = Yii::$app->has('user', true) ? Yii::$app->get('user')->getId(false) : '-';
        Yii::info("Logout request {$user_id}", 'user');

        if (Yii::$app->user->logout()) {
            Yii::info('Successful logout', 'user.logout');
        } else {
            Yii::warning('Unsuccessful logout', 'user.logout');
        }

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        throw new GoneHttpException();

        //        $model = new ContactForm();
//        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
//            Yii::$app->session->setFlash('contactFormSubmitted');
//
//            return $this->refresh();
//        }
//        return $this->render('contact', [
//                'model' => $model,
//        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        Yii::trace('About page display');
        return $this->render('about');
    }
}
