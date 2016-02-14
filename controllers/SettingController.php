<?php

namespace app\controllers;

use app\models\Setting;
use app\models\Auth;
use app\models\City;
use app\models\Role;
use app\models\State;
use Yii;
use app\models\User;
use app\models\UserSearch;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use yii\helpers\Html;

class SettingController extends BaseController
{
    public function allowed()
    {
        return [
            'User.Captcha',
        ];
    }

    public function behaviors()
    {
        return [

        ];
    }

    public function actionIndex()
    {
        $configFormModel = new Setting();
        $configFormModel->scenario = 'configForm';
        $models = Setting::find()->all();

        foreach ($models as $model) {
            switch ($model->key) {
                case Setting::LANGUAGE:
                    $configFormModel->language = $model->value;
                    break;

                case Setting::FROM_EMAIL:
                    $configFormModel->fromEmail = $model->value;
                    break;

                case Setting::FROM_NAME:
                    $configFormModel->fromName = $model->value;
                    break;

                case Setting::FB_PAGE:
                    $configFormModel->fbPage = $model->value;
                    break;
            }
        }

        if ($configFormModel->load(Yii::$app->request->post())) {
            $anyFailed = false;
//            $data = $_POST['Setting'];
//            $configFormModel->language = $data['language'];
//            $configFormModel->fromEmail = $data['fromEmail'];
//            $configFormModel->fromName = $data['fromName'];
//            $configFormModel->fbPage = $data['fbPage'];

            if ($configFormModel->validate()) {
                foreach ($models as $model) {
                    $model->scenario = 'update';
                    $isConfigChanged = false;
                    switch ($model->key) {
                        case Setting::LANGUAGE:
                            $model->value = $configFormModel->language;
                            $isConfigChanged = true;
                            break;

                        case Setting::FROM_EMAIL:
                            $model->value = $configFormModel->fromEmail;
                            $isConfigChanged = true;
                            break;

                        case Setting::FROM_NAME:
                            $model->value = $configFormModel->fromName;
                            $isConfigChanged = true;
                            break;

                        case Setting::FB_PAGE:
                            $model->value = $configFormModel->fbPage;
                            $isConfigChanged = true;
                            break;
                    }

                    if ($isConfigChanged) {
                        try {
                            if ($model->save(false)) {
                                Yii::$app->appLog->writeLog("Setting updated.Key:{$model->key},Value:{$model->value}");
                            } else {
                                Yii::$app->appLog->writeLog("Setting update falied.Key:{$model->key},Value:{$model->value}");
                                $anyFailed = true;
                            }
                        } catch (Exception $e) {
                            $anyFailed = true;
                            Yii::$app->appLog->writeLog("Setting update failed.Key:{$model->key},Value:{$model->value},error:{$e->getMessage()}");
                        }
                    }
                }
            } else {
                $anyFailed = true;
                Yii::$app->appLog->writeLog("Setting update failed.Validation errors:" . json_encode($configFormModel->errors));
                Yii::$app->appLog->writeLog(json_encode($configFormModel->attributes));
            }

            if ($anyFailed) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Settings update failed'));
            } else {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Settings updated'));

            }
        }

        return $this->render('index', [
            'model' => $configFormModel,
        ]);
    }

}
