<?php

namespace app\controllers;

use app\models\City;
use app\models\State;
use Yii;
use app\models\User;
use app\models\UserSearch;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends BaseController
{

    public function allowed()
    {
        return [
            'User.GetStates',
            'User.GetCities'
        ];
    }

    public function behaviors()
    {
        return [

        ];
    }

    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        Yii::$app->appLog->writeLog('List Users');

        $searchModel = new UserSearch();

        $params = (Yii::$app->request->isGet ? Yii::$app->request->queryParams : (Yii::$app->request->isPost ? Yii::$app->request->bodyParams : array()));
        $params['UserSearch']['type'] = User::SYSTEM;

        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        Yii::$app->appLog->writeLog('View User', ['id' => $id]);

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionGetStates()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
            $list = State::find()->andWhere(['country_id' => $id])->asArray()->all();
            $selected = null;
            if ($id != null && count($list) > 0) {
                $selected = '';
                foreach ($list as $i => $account) {
                    $out[] = ['id' => $account['id'], 'name' => $account['name']];
//                    if ($i == 0) {
//                        $selected = $account['id'];
//                    }
                }
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out]);
//                echo Json::encode(['output' => $out, 'selected' => $selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionGetCities()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
            $list = City::find()->andWhere(['state_id' => $id])->asArray()->all();
            $selected = null;
            if ($id != null && count($list) > 0) {
                $selected = '';
                foreach ($list as $i => $account) {
                    $out[] = ['id' => $account['id'], 'name' => $account['name']];
//                    if ($i == 0) {
//                        $selected = $account['id'];
//                    }
                }
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out]);
//                echo Json::encode(['output' => $out, 'selected' => $selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $sucMsg = Yii::t('app', 'User created.');
        $errMsg = Yii::t('app', 'User create failed.');

        $model = new User();
        $model->scenario = User::SCENARIO_CREATE;

        if ($model->load(Yii::$app->request->post())) {
            $model->password = $model->encryptPassword($model->formPassword);
            $model->type = User::SYSTEM;
            if ($model->saveModel()) {
                Yii::$app->session->setFlash('success', $sucMsg);
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', $errMsg);
            }
        } else {
            $model->status = User::ACTIVE;
            $model->timeZone = Yii::$app->params['defaultTimeZone'];
        }

        return $this->render('create', [
            'model' => $model

        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $sucMsg = Yii::t('app', 'User updated.');
        $errMsg = Yii::t('app', 'User update failed.');

        $model = $this->findModel($id);
        $model->scenario = User::SCENARIO_UPDATE;
        $curPassword = $model->password;

        if ($model->load(Yii::$app->request->post())) {
            if ('' == $model->formPassword) {
                $model->password = $curPassword;
            } else {
                $model->password = $model->encryptPassword($model->formPassword);
            }

            if ($model->saveModel()) {
                Yii::$app->session->setFlash('success', $sucMsg);
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', $errMsg);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $sucMsg = Yii::t('app', 'User was successfully deleted.');
        $errMsg = Yii::t('app', 'User could not be deleted.');

        $model = $this->findModel($id);
        if ($model->deleteModel()) {
            Yii::$app->session->setFlash('success', $sucMsg);
        } else {
            Yii::$app->session->setFlash('error', $errMsg);
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            Yii::$app->appLog->writeLog('The requested page does not exist', ['id' => $id]);
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Update own profile details.
     * @return mixed
     */
    public function actionMyAccount()
    {
        $sucMsg = Yii::t('app', 'Profile updated.');
        $errMsg = Yii::t('app', 'Profile update failed.');

        $id = Yii::$app->user->identity->id;
        $model = $this->findModel($id);
        $model->scenario = User::SCENARIO_MY_ACCOUNT;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->saveModel()) {
                Yii::$app->session->setFlash('success', $sucMsg);
            } else {
                Yii::$app->session->setFlash('error', $errMsg);
            }
        }

        return $this->render('myAccount', [
            'model' => $model,
        ]);
    }

    /**
     * Change password.
     * @return mixed
     */
    public function actionChangePassword()
    {
        $sucMsg = Yii::t('app', 'Password changed.');
        $errMsg = Yii::t('app', 'Password changed failed.');

        $id = Yii::$app->user->identity->id;
        $model = $this->findModel($id);
        $model->scenario = User::SCENARIO_CHANGE_PASSWORD;
        $model->curOldPassword = $model->password;

        if ($model->load(Yii::$app->request->post())) {

            $oldPassword = '';
            if ('' != $model->oldPassword) {
                $oldPassword = $model->oldPassword;
                $model->oldPassword = $model->encryptPassword($model->oldPassword);
            }

            $model->password = $model->encryptPassword($model->newPassword);

            if ($model->saveModel()) {
                Yii::$app->session->setFlash('success', $sucMsg);
                return $this->redirect(['change-password']);
            } else {
                Yii::$app->session->setFlash('error', $errMsg);
            }

            $model->oldPassword = $oldPassword;
        }

        return $this->render('changePassword', [
            'model' => $model,
        ]);
    }

    /**
     * Reset advertiser password and email
     * @param integer $id Advertiser id
     * @return mixed
     */
    public function actionForgetPassword($id)
    {
        $model = $this->findModel($id);
        $newPassword = $model->getNewPassword();
        $encryptedPw = $model->encryptPassword($newPassword);

        $model->password = $encryptedPw;

        try {
            if ($model->save()) {

                Yii::$app->session->setFlash('success', Yii::t('app', 'Password reset success.'));

                $message = Yii::t('app', 'Dear {name}, Your {productName} password has been reset. New password is:{newPassword}', [
                    'name' => $model->firstName,
                    'productName' => Yii::$app->params['productName'],
                    'newPassword' => $newPassword
                ]);

                $response = Yii::$app->mailer
                    ->compose('@app/views/email-template/notificationTemplate', ['content' => $message])
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['productName']])
                    ->setTo($model->email)
                    ->setSubject(Yii::t('app', '{name} Reset Password', ['name' => Yii::$app->params['productName']]))
                    ->send();

                if (!$response) {
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Email sending failed. Try again later.'));
                }
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Password reset failed.'));
            }
        } catch (Exception $e) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Password reset failed.'));
        }

        return $this->redirect(['advertiser']);
    }

    public function actionGetLocation($type, $id)
    {
        try {
            if (!isset($_GET['type']) || empty($_GET['type'])) {
                throw new exception("Type is not set.");
            }
            $type = $_GET['type'];
            if ($type == 'getCountries') {
                $res = ArrayHelper::map(\app\models\Country::find()->all(), 'id', 'name');
                $data = array('status' => 'success', 'tp' => 1, 'msg' => "Countries fetched successfully.", 'result' => $res);

            }

            if ($type == 'getStates') {
                if (!isset($_GET['id']) || empty($_GET['id'])) {
                    throw new exception("Country Id is not set.");
                }
                $countryId = $_GET['id'];
                $res = ArrayHelper::map(\app\models\State::find()->where('country_id = :countryId', ['countryId' => $countryId])->all(), 'id', 'name');
                $data = array('status' => 'success', 'tp' => 1, 'msg' => "States fetched successfully.", 'result' => $res);

            }

            if ($type == 'getCities') {
                if (!isset($_GET['id']) || empty($_GET['id'])) {
                    throw new exception("State Id is not set.");
                }
                $stateId = $_GET['id'];
                $res = ArrayHelper::map(\app\models\City::find()->where('state_id = :stateId', ['stateId' =>
                    $stateId])->all(), 'id', 'name');
                $data = array('status' => 'success', 'tp' => 1, 'msg' => "Cities fetched successfully.", 'result' => $res);

            }

        } catch (Exception $e) {
            $data = array('status' => 'error', 'tp' => 0, 'msg' => $e->getMessage());
        } finally {
            echo json_encode($data);
        }
    }

    /**
     * Show map view to find the location
     * @return mixed
     */
    public function actionMap()
    {
        $this->layout = 'map';
        return $this->render('map', [
            //'model' => $model,
        ]);
    }
}

