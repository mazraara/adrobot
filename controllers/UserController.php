<?php

namespace app\controllers;

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
            'User.Captcha',
            'User.GetStates',
            'User.GetCities',
            'User.Register',
            'User.EmailConfirm',
            'User.ResetPassword',
            'User.ForgetPassword',
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionRegister()
    {
        $this->layout = 'guest';

        $model = new User();
        $model->scenario = 'register';

        if ($model->load(Yii::$app->request->post())) {
            $model->roleName = Role::SYSTEM_PARTNER;
            $model->isEmailConfirmed = 0;
            $model->status = User::ACTIVE;
            $model->createdAt = Yii::$app->util->getUtcDateTime();
            $model->createdById = -1;
            $model->type = User::TYPE_SUPPORTER;
            if ($model->validate()) {
                $password = $model->password;
                $model->password = $model->encryptPassword();
                try {
                    if ($model->save(false)) {
                        Yii::$app->appLog->writeLog("User registerd.", [$model->attributes]);
                        $link = Url::toRoute(['user/email-confirm', 'q' => (string)$model->id], true);
                        $message = Yii::t('app', 'Thank you for register with {name}. Please click {here} to complete your registration', [
                            'name' => Yii::$app->params['productName'],
                            'here' => Html::a(Yii::t('app', 'here'), $link)
                        ]);

                        $response = Yii::$app->mailer->compose('@app/views/emailTemplate/notificationTemplate', ['content' => $message])
                            ->setFrom(Yii::$app->params['supportEmail'])
                            ->setTo($model->email)
                            ->setSubject(Yii::t('app', '{name} Registration', ['name' => Yii::$app->params['productName']]))
                            ->send();

                        if ($response) {
                            Yii::$app->appLog->writeLog("Email confirmation mail sent.");
                        } else {
                            Yii::$app->appLog->writeLog("Email confirmation mail sending failed");
                        }

                        $model = new User();
                        Yii::$app->session->setFlash('success', Yii::t('app', 'Thank you for registering with {name}. Please confirm your email to complete the registration.', ['name' => Yii::$app->params['productName']]));
                        Yii::$app->appLog->writeLog("User registration success.", [$model->attributes]);
                    } else {
                        Yii::$app->appLog->writeLog("User registration failed.", [$model->attributes]);
                        Yii::$app->session->setFlash('error', Yii::t('app', 'Registration failed.'));
                    }
                } catch (Exception $e) {
                    Yii::$app->appLog->writeLog("User registration failed.", [$e->getMessage(), $model->attributes]);
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Registration failed.'));
                }
                $model->password = $password;
            } else {
                Yii::$app->appLog->writeLog("User registration failed. Validation failed.", [$model->errors]);
            }
        }

        return $this->render('register', [
            'model' => $model,
        ]);
    }

    public function actionEmailConfirm($q)
    {
        $this->layout = 'guest';
        $id = $q;//'557e8ac4dc78c19c12000029';
        $model = $this->findModel($id);

        if (null != $model) {
            $model->isEmailConfirmed = 1;
            try {
                if ($model->save()) {
                    Yii::$app->appLog->writeLog("User confirmed the email. Status updated", [$model->attributes]);
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Registration success. Click {here} to login.', ['here' => Html::a(Yii::t('app', '<strong>here</strong>'), ['site/login'])]));
                } else {
                    Yii::$app->appLog->writeLog("User confirmed the email. Status update failed", [$model->attributes]);
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Registration failed.'));
                }
            } catch (Exception $e) {
                Yii::$app->appLog->writeLog("User confirmed the email. Status update failed ", [$e->getMessage(), $model->attributes]);
                Yii::$app->session->setFlash('error', Yii::t('app', 'Registration failed.'));
            }
        } else {
            Yii::$app->appLog->writeLog("User confirmed the email. But not registered.", [$model->attributes]);
            Yii::$app->session->setFlash('error', Yii::t('app', 'Looks like you havent registered with us.'));
        }

        return $this->render('registerStatus', [
            'model' => $model,
        ]);
    }


    /**
     * Reset advertiser password and email
     * @param integer $id Advertiser id
     * @return mixed
     */
    public function actionResetPassword($id)
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

                $response = Yii::$app->mailer->compose('@app/views/emailTemplate/notificationTemplate', ['content' => $message])
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
                }
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out]);
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
                }
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out]);
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
            // process uploaded image file instance
            $image = $model->uploadImage();

            $model->password = $model->encryptPassword($model->formPassword);
            $model->type = User::SYSTEM;
            if ($model->saveModel()) {
                // upload only if valid uploaded file instance found
                if ($image !== false) {
                    $path = $model->getImageFile();
                    $image->saveAs($path);
                }
                Yii::$app->session->setFlash('success', $sucMsg);
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', $errMsg);
                Yii::$app->appLog->writeLog("User create failed." . json_encode($model->getLastError()));

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
        $oldFile = $model->getImageFile();
        $oldAvatar = $model->avatar;
        $oldFileName = $model->fileName;

        $model->scenario = User::SCENARIO_UPDATE;
        $curPassword = $model->password;

        if ($model->load(Yii::$app->request->post())) {

            // process uploaded image file instance
            $image = $model->uploadImage();

            // revert back if no valid file instance uploaded
            if ($image === false) {
                $model->avatar = $oldAvatar;
                $model->fileName = $oldFileName;
            }

            if ($model->saveModel()) {
                // upload only if valid uploaded file instance found
                if ($image !== false && unlink($oldFile)) { // delete old and overwrite
                    $path = $model->getImageFile();
                    $image->saveAs($path);
                }

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
            if (!$model->deleteImage()) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Error deleting image'));
                Yii::$app->appLog->writeLog("Error deleting image.");
            }
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
    public function actionForgetPassword()
    {
        $this->layout = 'forget_password';
        $model = new User();
        $model->scenario = 'resetPassword';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model = User::find()->where('email=:email', [':email' => $model->email])->one();
            $newPassword = $model->getNewPassword();
            $encryptedPw = $model->encryptPassword($newPassword);
            $model->password = $encryptedPw;

            try {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Password reset success.'));
                    $message = Yii::t('app', "Dear {name}, <br><br> Your {productName} password has been reset. <br><br> New password is:{newPassword}", [
                        'name' => $model->firstName,
                        'productName' => Yii::$app->params['productName'],
                        'newPassword' => $newPassword
                    ]);

                    $response = Yii::$app->mailer
                        ->compose('@app/views/emailTemplate/notificationTemplate', ['content' => $message])
                        ->setFrom(Yii::$app->params['supportEmail'])
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
        }

        return $this->render('forget', [
            'model' => $model,
        ]);
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

