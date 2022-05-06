<?php

namespace app\controllers;

use app\models\search\LogSearch;
use app\models\search\PopularBrowserSearch;
use app\models\search\RequestsCountSearch;
use app\services\parsers\LogFileParser;
use app\services\progressbars\ConsoleProgressBar;
use app\services\readers\LogFileReader;
use app\services\writers\DBWriter;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionTest()
    {
        set_time_limit(0);
        $startTime = time();
        $parser = new LogFileParser(
            new LogFileReader('%h %l %u %t "%m %U %P" %>s %O "%{Referer}i" \"%{User-Agent}i"'),
            new DBWriter(\yii\db\ActiveRecord::class),
            new ConsoleProgressBar(),
        );
        $parser->init('C:\OpenServer\domains\st.loc\tmp\test\7k');
        $parser->parse([]);
        $parser->setFinishTime();
        $content = (Yii::t('app', 'Total processing time: {time} sec', ['time' => (time() - $startTime)]));
        return $this->render('test', ['content' => $content]);
    }
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $requestsCountSearchModel = new RequestsCountSearch();
        $requestsCountDataProvider = $requestsCountSearchModel->search(Yii::$app->request->queryParams);

        $popularBrowserSearchModel = new PopularBrowserSearch();
        $popularBrowserDataProvider = $popularBrowserSearchModel->search(Yii::$app->request->queryParams);

        $logSearchModel = new LogSearch();
        $logDataProvider = $logSearchModel->search(Yii::$app->request->queryParams);


        return $this->render('index', [
            'requestsCountSearchModel' => $requestsCountSearchModel,
            'requestsCountDataProvider' => $requestsCountDataProvider,

            'popularBrowserSearchModel' => $popularBrowserSearchModel,
            'popularBrowserDataProvider' => $popularBrowserDataProvider,

            'logSearchModel' => $logSearchModel,
            'logDataProvider' => $logDataProvider,
        ]);
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

        $model->password = '';
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
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
