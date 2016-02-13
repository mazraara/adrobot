<?php

namespace app\models;

use Yii;

class Base extends \yii\db\ActiveRecord
{
    private $_oldAttributes = array();

    const STATUS_DEACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const CRYPT_SALT = '$6$rounds=5000$V%7^CFF73;8^h~E$';

    private $_statuses = [
        self::STATUS_DEACTIVE => 'Deactive',
        self::STATUS_ACTIVE => 'Active',
    ];

    public function getOldAttributes()
    {
        return $this->_oldAttributes;
    }

    public function setOldAttributes($value)
    {
        $this->_oldAttributes = $value;
    }

    /**
     * Returns the statuses.
     * @return array statuses array.
     */
    public function getStatuses()
    {
        return $this->_statuses;
    }

    public function beforeValidate()
    {
        // updatedAt
        if ($this->hasAttribute('updatedAt'))
            $this->updatedAt = Yii::$app->util->getUtcDateTime();

        // updatedById
        if ($this->hasAttribute('updatedById') && null == $this->updatedById) {
            if (Yii::$app instanceof \yii\web\Application && is_object(Yii::$app->user->identity)) {
                $this->updatedById = Yii::$app->user->identity->id;
            } elseif (Yii::$app instanceof \yii\console\Application) {
                $this->updatedById = -1;
            } else {
                $this->updatedById = null;
            }
        }

        // New record
        if ($this->isNewRecord) {
            // createdAt
            if ($this->hasAttribute('createdAt'))
                $this->createdAt = Yii::$app->util->getUtcDateTime();

            // createdById
            if ($this->hasAttribute('createdById') && null == $this->createdById) {
                if (Yii::$app instanceof \yii\web\Application && is_object(Yii::$app->user->identity)) {
                    $this->createdById = Yii::$app->user->identity->id;
                } elseif (Yii::$app instanceof \yii\console\Application) {
                    $this->createdById = -1;
                } else {
                    $this->createdById = null;
                }
            }

            // updatedAt
            if ($this->hasAttribute('updatedAt'))
                $this->updatedAt = null;

            // updatedById
            if ($this->hasAttribute('updatedById'))
                $this->updatedById = null;
        }
        return parent::beforeValidate();
    }

    public function afterValidate()
    {
        $attrs = $this->getAttributes();

        foreach ($attrs as $name => $value) {
            $this->$name = trim($value);

            if ($this->$name === '')
                $this->$name = null;
        }
        return parent::afterValidate();
    }


    public function getLastError()
    {
        $errorData = [];
        if ($this->hasErrors()) {
            $errors = $this->getFirstErrors();
            reset($errors);
            list($attribute, $message) = each($errors);
            $errorData = [
                'attribute' => $attribute,
                'message' => $message
            ];
        }

        return $errorData;
    }

    public function afterFind()
    {
        $this->setOldAttributes($this->getAttributes());
    }

    /*public function getLastError()
    {
        $errorsArr = $this->getErrors();

        if (count($errorsArr) > 0) {
            $errors = array_values($errorsArr);
            return $errors[0][0];
        }
        return '';
    }*/

    /**
     * Generic function to save any model
     * @return boolean $status true of false.
     */
    public function saveModel()
    {
        $modelName = get_class($this);

        $status = false;
        if ($this->validate()) {
            try {
                if ($this->save()) {
                    $status = true;
                    Yii::$app->appLog->writeLog("{$modelName} record saved.", ['attributes' => $this->attributes]);
                } else {
                    Yii::$app->appLog->writeLog("{$modelName} record save failed.", ['errors' => $this->errors, 'attributes' => $this->attributes]);
                }
            } catch (\Exception $e) {
                Yii::$app->appLog->writeLog("{$modelName} record save failed", ['exception' => $e->getMessage(), 'attributes' => $this->attributes]);
            }
        } else {
            Yii::$app->appLog->writeLog("{$modelName} record save failed. Validation errors.", ['errors' => $this->errors, 'attributes' => $this->attributes]);
        }

        return $status;
    }

    /**
     * Generic function to delete any model
     * @return boolean $status true of false.
     */
    public function deleteModel()
    {
        $modelName = get_class($this);
        $status = false;

        try {
            if ($this->delete()) {
                $status = true;
                Yii::$app->appLog->writeLog("{$modelName} record deleted.", ['attributes' => $this->attributes]);
            } else {
                Yii::$app->appLog->writeLog("{$modelName} record delete failed.", ['attributes' => $this->attributes]);
            }
        } catch (\Exception $e) {
            Yii::$app->appLog->writeLog("{$modelName} record delete failed.", ['exception' => $e->getMessage(), 'attributes' => $this->attributes]);
        }

        return $status;
    }
}
