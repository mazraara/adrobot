<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

<?php $form = ActiveForm::begin(['enableClientValidation' => false]); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'firstName') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'lastName') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'email')->textInput(['readonly' => true]) ?>

        </div>
        <div class="col-md-6">
            <?=
            $form->field($model, 'timeZone')->dropDownList(
                Yii::$app->util->getTimeZoneList(), ['prompt' => Yii::t('app', '- TimeZone -')]
            );
            ?>
        </div>
    </div>

    <div class="form-group">
    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-info']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>
