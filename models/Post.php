<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Post".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $publishDate
 * @property string $address
 * @property string $geoLocaton
 * @property string $keywords
 * @property integer $status
 * @property string $rejectReason
 * @property string $createdAt
 * @property string $updatedAt
 * @property integer $createdById
 * @property integer $updatedById
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'publishDate', 'status', 'createdAt', 'createdById'], 'required'],
            [['id', 'status', 'createdById', 'updatedById'], 'integer'],
            [['content', 'keywords'], 'string'],
            [['publishDate', 'createdAt', 'updatedAt'], 'safe'],
            [['title', 'rejectReason'], 'string', 'max' => 100],
            [['address'], 'string', 'max' => 256],
            [['geoLocaton'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'publishDate' => Yii::t('app', 'Publish Date'),
            'address' => Yii::t('app', 'Address'),
            'geoLocaton' => Yii::t('app', 'Geo Locaton'),
            'keywords' => Yii::t('app', 'Keywords'),
            'status' => Yii::t('app', 'Status'),
            'rejectReason' => Yii::t('app', 'Reject Reason'),
            'createdAt' => Yii::t('app', 'Created At'),
            'updatedAt' => Yii::t('app', 'Updated At'),
            'createdById' => Yii::t('app', 'Created By ID'),
            'updatedById' => Yii::t('app', 'Updated By ID'),
        ];
    }

    /**
     * @inheritdoc
     * @return PostQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PostQuery(get_called_class());
    }
}
