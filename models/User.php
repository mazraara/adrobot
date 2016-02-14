<?php

namespace app\models;

use Yii;
use app\models\Base;

/**
 * This is the model class for table "User".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $firstName
 * @property string $lastName
 * @property string $gender
 * @property string $dateOfBirth
 * @property string $streetAddress
 * @property string $zip
 * @property integer $countryId
 * @property integer $stateId
 * @property integer $cityId
 * @property string $geoLocaton
 * @property string $email
 * @property string $timeZone
 * @property string $roleName
 * @property string $isEmailConfirmed
 * @property integer $type
 * @property integer $status
 * @property integer $lastLoggedTime
 * @property integer $lastVisitAgent
 * @property integer $lastVisitIp
 * @property string $phone
 * @property string $createdAt
 * @property string $updatedAt
 * @property integer $createdById
 * @property integer $updatedById
 *
 * @property City $city
 * @property Country $country
 * @property Role $roleName0
 * @property State $state
 */
class User extends Base
{
    // User statuses
    const ACTIVE = 1;
    const INACTIVE = 2;

    const TYPE_SYSTEM_USER = -1;
    const TYPE_OWNER = 1;
    const TYPE_SUPPORTER = 2;

    // User gender
    const MALE = 1;
    const FEMALE = 2;
    // User types
    const SYSTEM = 3;

    // Validation scenarios
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_MY_ACCOUNT = 'myAccount';
    const SCENARIO_CHANGE_PASSWORD = 'changePassword';
    const SCENARIO_REGISTER = 'register';
    const SCENARIO_RESET_PASSWORD = 'resetPassword';

    public $confPassword;
    public $captcha;
    public $oldPassword;
    public $newPassword;
    public $curOldPassword;
    public $formPassword;

    private $_statuses = array(
        self::ACTIVE => 'Active',
        self::INACTIVE => 'Inactive',
    );

    private $_types = array(
        self::TYPE_SYSTEM_USER => 'System User',
        self::TYPE_OWNER => 'Owner',
        self::TYPE_SUPPORTER => 'Supporter',
    );

    private $_genders = array(
        self::MALE => 'Male',
        self::FEMALE => 'Female',
    );

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'User';
    }

    public function behaviors()
    {
        return [

        ];
    }

    /**
     * Returns the user statuses.
     * @return array statuses array.
     */
    public function getStatuses()
    {
        return $this->_statuses;
    }

    /**
     * Returns the user statuses.
     * @return array statuses array.
     */
    public function getGenders()
    {
        return $this->_genders;
    }

    /**
     * Returns the user types.
     * @return array types array.
     */
    public function getTypes()
    {
        return $this->_types;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // common
            [['email'], 'email', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['username'], 'unique', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['email'], 'unique', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['username', 'password'], 'string', 'max' => 15, 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['firstName', 'lastName', 'roleName'], 'string', 'max' => 30, 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['email', 'timeZone'], 'string', 'max' => 60, 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['phone'], 'string', 'max' => 20, 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['firstName', 'lastName', 'username'], 'match', 'pattern' => '/^[a-zA-Z]+$/', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['createdById', 'updatedById'], 'integer', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['type', 'status'], 'integer', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['streetAddress'], 'string', 'max' => 128],
            [['zip'], 'string', 'max' => 16],
            [['geoLocaton'], 'string', 'max' => 64],

            // Create
            [['firstName', 'lastName', 'username', 'email', 'roleName', 'timeZone', 'gender', 'countryId', 'stateId', 'cityId', 'dateOfBirth'], 'required', 'on' => [self::SCENARIO_CREATE]],
            [['password', 'confPassword', 'formPassword'], 'required', 'on' => [self::SCENARIO_CREATE]],
            [['formPassword'], 'checkPasswordStrength', 'params' => ['min' => 7, 'allowEmpty' => false], 'on' => [self::SCENARIO_CREATE]],
            [['confPassword'], 'compare', 'compareAttribute' => 'formPassword', 'operator' => '==', 'on' => [self::SCENARIO_CREATE]],

            // Update
            [['firstName', 'lastName', 'username', 'email', 'roleName', 'timeZone', 'gender', 'countryId', 'stateId', 'cityId', 'dateOfBirth'], 'required', 'on' => [self::SCENARIO_UPDATE]],
            [['formPassword'], 'checkPasswordStrength', 'params' => ['min' => 7, 'allowEmpty' => true], 'on' => [self::SCENARIO_UPDATE]],
            [['confPassword'], 'compare', 'compareAttribute' => 'formPassword', 'operator' => '==', 'on' => [self::SCENARIO_UPDATE]],

            // system user > myAccountSysUser
            [['firstName', 'lastName', 'username', 'email', 'roleName', 'timeZone'], 'required', 'on' => 'myAccountSysUser'],

            // myAccount
            [['firstName', 'lastName', 'username', 'email', 'roleName', 'timeZone'], 'required', 'on' => [self::SCENARIO_MY_ACCOUNT]],

            // changePassword
            [['oldPassword', 'newPassword', 'confPassword'], 'required', 'on' => [self::SCENARIO_CHANGE_PASSWORD]],
            [['oldPassword'], 'compare', 'compareValue' => $this->curOldPassword, 'operator' => '==', 'type' => 'string', 'on' => [self::SCENARIO_CHANGE_PASSWORD]],
            [['newPassword'], 'checkPasswordStrength', 'params' => ['min' => 7, 'allowEmpty' => false], 'on' => [self::SCENARIO_CHANGE_PASSWORD]],
            [['confPassword'], 'compare', 'compareAttribute' => 'newPassword', 'operator' => '==', 'type' => 'string', 'on' => [self::SCENARIO_CHANGE_PASSWORD]],

            // Forgot password
            [['email'], 'required', 'on' => [self::SCENARIO_RESET_PASSWORD]],
            [['email'], 'email', 'on' => [self::SCENARIO_RESET_PASSWORD]],
            [['email'], 'checkUserExist', 'on' => [self::SCENARIO_RESET_PASSWORD]],

            // User registration
            [['firstName', 'lastName', 'email', 'username', 'password', 'gender', 'dateOfBirth', 'confPassword', 'roleName', 'timeZone', 'captcha'], 'required', 'on' => [self::SCENARIO_REGISTER]],
            [['password'], 'checkPasswordStrength', 'on' => [self::SCENARIO_REGISTER], 'params' => ['min' => 7, 'allowEmpty' => false]],
            [['confPassword'], 'compare', 'compareAttribute' => 'password', 'on' => [self::SCENARIO_REGISTER], 'operator' => '=='],
            ['captcha', 'captcha', 'captchaAction' => 'user/captcha', 'on' => [self::SCENARIO_REGISTER]],
            [['username'], 'unique', 'on' => [self::SCENARIO_REGISTER]],


            // Safe
            [['createdAt', 'updatedAt', 'captcha', 'password', 'about', 'isEmailConfirmed', 'createdById'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
            'formPassword' => Yii::t('app', 'Password'),
            'oldPassword' => Yii::t('app', 'Old Password'),
            'firstName' => Yii::t('app', 'First Name'),
            'lastName' => Yii::t('app', 'Last Name'),
            'gender' => Yii::t('app', 'Gender'),
            'dateOfBirth' => Yii::t('app', 'Date Of Birth'),
            'streetAddress' => Yii::t('app', 'Street Address'),
            'zip' => Yii::t('app', 'Zip'),
            'countryId' => Yii::t('app', 'Country'),
            'stateId' => Yii::t('app', 'State'),
            'cityId' => Yii::t('app', 'City'),
            'geoLocaton' => Yii::t('app', 'Geo Locaton'),
            'email' => Yii::t('app', 'Email'),
            'confPassword' => Yii::t('app', 'Confirm Password'),
            'newPassword' => Yii::t('app', 'New Password'),
            'timeZone' => Yii::t('app', 'Time Zone'),
            'roleName' => Yii::t('app', 'Role'),
            'lastVisitIp' => Yii::t('app', 'Last IP'),
            'lastVisitAgent' => Yii::t('app', 'Last Visited Browser'),
            'lastLoggedTime' => Yii::t('app', 'Last Logging Time'),
            'type' => Yii::t('app', 'Type'),
            'status' => Yii::t('app', 'Status'),
            'phone' => Yii::t('app', 'Phone'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedAt' => Yii::t('app', 'Updated At'),
            'createdById' => Yii::t('app', 'Created By'),
            'updatedById' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * Encrypt password
     * @return string crypt encrypted password.
     */
    public function encryptPassword($password = '')
    {
        $pass = ('' == $password ? $this->password : $password);
        return crypt($pass, Yii::$app->params['salt']);
    }

    /**
     * Check password strength
     * @param string $attribute attribute name
     * @params array $params extra prameters to be passed to validation function
     * @return null
     */
    public function checkPasswordStrength($attribute, $params)
    {
        if ($params['allowEmpty'] && '' == $this->$attribute) {
            return true;
        } else {
            if (preg_match("/^.*(?=.{" . $params['min'] . ",})(?=.*\d)(?=.*[a-zA-Z])(?=.*[-@_#&.]).*$/", $this->$attribute)) {
                return true;
            } else {
                $this->addError($attribute, Yii::t('app', '{attribute} is weak. {attribute} must contain at least {min} characters, at least one letter, at least one number and at least one symbol(-@_#&.).', ['min' => $params['min'], 'attribute' => $this->getAttributeLabel($attribute)]));
            }
        }
    }

    /**
     * Check if user exist to reset password
     * @param string $attribute attribute name
     * @params array $params extra prameters to be passed to validation function
     * @return null
     */
    public function checkUserExist($attribute, $params)
    {
        $model = self::find()->where('email=:email', [':email' => $this->email])->one();
        if (is_null($model)) {
            $this->addError($attribute, Yii::t('app', 'No matching account for the entered email.'));
        } else
            return true;
    }

    /**
     * Generate new password for reset, according to password plicy
     * @return string $password random password. e.g.: abcd123.@
     */
    public function getNewPassword()
    {
        $letters = 'bcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ';
        $numbers = '0123456789';
        $symbols = '-@_#&.';

        $password = substr(str_shuffle($letters), 0, 4);
        $password .= substr(str_shuffle($numbers), 0, 2);
        $password .= substr(str_shuffle($symbols), 0, 2);

        return $password;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'cityId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'countryId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::className(), ['id' => 'stateId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::className(), ['name' => 'roleName']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'createdById']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updatedById']);
    }

    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * Get any of user attribute by its id
     * @parm string $attribute attribute name
     */
    public function getAttributeValueById($id, $attribute)
    {
        $model = User::findOne($id);
        if (!empty($model))
            return $model->$attribute;
        else
            return '';
    }


    public static function getFullNameById($id)
    {
        if ($id === null)
            return null;

        $model = self::find($id)->one();

        if ($model === null)
            return null;

        return $model->firstName . ' ' . $model->lastName;
    }

    /**
     * Returns statuses list for dropdown.
     * @return array statuses
     */
    public function getStatusesList($prompt = false)
    {
        return $prompt ? (array('' => Yii::t('app', '- Status -')) + $this->_statuses) : $this->_statuses;
    }

    /**
     * Return users list for dropdown options
     */
    public static function getUserList()
    {
        $list = [];
        $models = User::find()->where([])->all();
        if (!empty($models)) {
            foreach ($models as $model) {
                $list[$model->id] = "{$model->firstName} {$model->lastName}";
            }
        }

        return $list;
    }
}
