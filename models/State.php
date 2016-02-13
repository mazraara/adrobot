<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "State".
 *
 * @property integer $id
 * @property string $name
 * @property integer $country_id
 * @property Country $country
 */
class State extends Base
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'State';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'country_id'], 'required'],
            [['country_id'], 'integer'],
            [['name'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'country_id' => Yii::t('app', 'Country'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'country_id']);
    }
}
