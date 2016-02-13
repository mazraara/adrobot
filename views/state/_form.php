<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Role;
use yii\jui\DatePicker;
use app\models\User;
use yii\helpers\Url;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use app\models\Country;

use app\assets\TinyMceAsset;

/* @var $this yii\web\View */
/* @var $model app\models\Hunt */
/* @var $form yii\widgets\ActiveForm */

TinyMceAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php
    $form = ActiveForm::begin([
        'id' => 'userForm',
        'enableClientValidation' => false,
    ]);
    ?>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'name') ?>
        </div>
        <div class="col-md-3">
            <?=
            // Top most parent
            $form->field($model, 'country_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(Country::find()->asArray()->all(), 'id', 'name'),
                'options' => ['placeholder' => Yii::t('app', '- Country -'), 'id' => 'cat-id'],
                'pluginOptions' => [
                    'allowClear' => true
                ],

            ]);
            ?>
        </div>

    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-info' : 'btn btn-info']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>