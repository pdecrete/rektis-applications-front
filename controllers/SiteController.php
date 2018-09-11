<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
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
            return $this->render('index-guest', ['period_open' => (1 === \app\models\Config::getConfig('enable_applications'))]);
        } else {
            if (\Yii::$app->user->identity->isAdmin() ||
                \Yii::$app->user->identity->isSupervisor()) {
                return $this->redirect(['admin/index']);
            } else {
                $user = Applicant::findOne(['vat' => \Yii::$app->user->getIdentity()->vat, 'specialty' => \Yii::$app->user->getIdentity()->specialty]);
                if ($user->state == Applicant::DENIED_TO_APPLY) {
                    return $this->render('denied-application');
                } elseif ($user->agreedterms == null) {
                    \Yii::$app->response->redirect(['application/request-agree']);
                }

                return $this->render('index', [
                    'has_applications' => !empty($user->applications),
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
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        if (0 === \app\models\Config::getConfig('enable_applications')) {
            Yii::$app->session->addFlash('info', "Οι πληροφορίες είναι διαθέσιμες ταυτόχρονα με την ενεργοποίηση των αιτήσεων.");
            return $this->goHome();
        }

        Yii::trace('About page display');
        return $this->render('about', [
            'information' => \app\models\Page::getPageContent('about')
        ]);
    }
}
