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

$this->pageTitle = Yii::t('app', 'Configuration');
$this->pageTitleDescription = Yii::t('app', 'Update system configurations');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="settings-create">
    <div class="settings-form">
        <?php
        $form = ActiveForm::begin([
            'id' => 'settingForm',
            'enableClientValidation' => false,
        ]);
        ?>
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'language') ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'fromEmail') ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'fromName') ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'fbPage') ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-info']) ?>
        </div>

        <?php ActiveForm::end(); ?>


    </div>
</div>