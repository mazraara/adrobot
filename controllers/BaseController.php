<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use yii\helpers\BaseInflector;

/**
 * All other controllers get extended from this controller. Common controller activities can be
 * defined here
 */
class BaseController extends Controller
{

    public function allowed()
    {
        return array();
    }

    public function beforeAction($action)
    {
        Yii::$app->appLog->action = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
        Yii::$app->language = isset(Yii::$app->session['lang']) ? Yii::$app->session['lang'] : 'en-US'; //todo: to be removed

        $controllerId = BaseInflector::camelize(Yii::$app->controller->id);
        $actionId = BaseInflector::camelize(Yii::$app->controller->action->id);
        $permissionName = "{$controllerId}.{$actionId}";
        $allowedActions = Yii::$app->controller->allowed();

        if (Yii::$app->getUser()->isGuest &&
            !in_array(strtolower($permissionName), array_map('strtolower', $allowedActions)) &&
            Yii::$app->getRequest()->url !== Url::to(Yii::$app->getUser()->loginUrl)
        ) {
            Yii::$app->getResponse()->redirect(Yii::$app->getUser()->loginUrl);
        }

        if (!Yii::$app->getUser()->isGuest &&
            !in_array(strtolower($permissionName), array_map('strtolower', $allowedActions))
        ) {
            if (!Yii::$app->user->can($permissionName)) {
                Yii::$app->getResponse()->redirect(Yii::$app->params['accessDeniedUrl']);
            }
        }

        return true;
    }
}

?>