<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Setting".
 *
 * @property string $key
 * @property string $value
 */
class Setting extends Base
{
    const LANGUAGE = 'LANGUAGE';
    const FROM_EMAIL = 'FROM_EMAIL';
    const FROM_NAME = 'FROM_NAME';
    const FB_PAGE = 'FB_PAGE';

    public $language = null;
    public $fromEmail = null;
    public $fromName = null;
    public $fbPage = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'value'], 'required', 'on' => 'update'],
            [['language', 'fromEmail', 'fromName', 'fbPage'], 'required', 'on' => 'configForm'],
            [['key', 'value'], 'string', 'max' => 60],
            // Safe
            [['key', 'value', 'language', 'fromEmail', 'fromName', 'fbPage'], 'safe'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'key' => Yii::t('app', 'Key'),
            'value' => Yii::t('app', 'Value'),
            'language' => Yii::t('app', 'Language'),
            'fromEmail' => Yii::t('app', 'From Email'),
            'fromName' => Yii::t('app', 'From Name'),
            'fbPage' => Yii::t('app', 'Facebook Page'),
        ];
    }

    /**
     * Retrive system configurations.
     * @return mixed array Configuration array
     */
    public function getConfigurations()
    {
        $configurations = array();
        $configModels = Setting::find()->all();

        foreach ($configModels as $configModel) {
            $configurations[$configModel->key] = $configModel->value;
        }

        return $configurations;
    }

    /**
     * @inheritdoc
     * @return SettingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SettingQuery(get_called_class());
    }
}
