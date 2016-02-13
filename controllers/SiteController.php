<?php

namespace app\controllers;

use Yii;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends BaseController
{

    public function behaviors()
    {
        return [
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
            ],
        ];
    }

    public function allowed()
    {
        return [
            'Site.Index',
            'Site.Login',
            'Site.AccessDenied',
            'Site.Error',
            'Site.Logout',
            'Site.Captcha',
        ];
    }

    public function actionIndex()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->render('index', []);
        } else {
            return $this->redirect(['login']);
        }
    }

    public function actionLogin()
    {
        $this->layout = 'login';
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Yii::$app->appLog->writeLog('Login success');
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->appLog->writeLog('Logout success');
        Yii::$app->user->logout();
        return $this->redirect(['login']);
    }
}
