<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\User;
use yii\captcha\Captcha;
use kartik\select2\Select2;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */

$this->pageTitle = Yii::t('app', 'Register');
$this->pageTitleDescription = Yii::t('app', 'Register with {name}', ['name' => Yii::$app->params['productName']]);
//$this->params['breadcrumbs'][] = Yii::t('app', 'Register');
?>

<div class="user-form">

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'enableClientValidation' => false,
        //'enableAjaxValidation' => true,
        'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-sm-4',
                'offset' => 'col-sm-offset-4',
                'wrapper' => 'col-sm-8',
                'error' => '',
                'hint' => '',
            ],
        ],
    ]); ?>
    <div class="row">

        <div class="col-md-6">
            <?= $form->field($model, 'firstName')->textInput(['placeholder' => $model->getAttributeLabel('firstName')]) ?>
            <?= $form->field($model, 'lastName')->textInput(['placeholder' => $model->getAttributeLabel('lastName')]) ?>
            <?= $form->field($model, 'username')->textInput(['placeholder' => $model->getAttributeLabel('lastName')]) ?>
            <?= $form->field($model, 'email')->textInput(['placeholder' => $model->getAttributeLabel('email')]) ?>
            <?= $form->field($model, 'password')->passwordInput(['autocomplete' => "off", 'placeholder' => $model->getAttributeLabel('passowrd')]) ?>
            <?= $form->field($model, 'confPassword')->passwordInput(['autocomplete' => "off", 'placeholder' => $model->getAttributeLabel('confPassword')]) ?>
        </div>
        <div class="col-md-6">
            <?=
            $form->field($model, 'dateOfBirth')->widget(DatePicker::classname(), [
                'name' => 'date',
                'value' => date('Y-M-d'),
                'dateFormat' => 'yyyy-MM-dd',
                'options' => [
                    'id' => 'startDate',
                    'class' => 'form-control',
                    'readOnly' => true,
                ],
                'clientOptions' => [
                    'changeMonth' => true,
                    'changeYear' => true,
                    'showButtonPanel' => true,
                ],
            ]);
            ?>
            <?= $form->field($model, 'gender')->radioList($model->getGenders(), array('template' => "<td>{input}</td><td>{label}</td>")); ?>
            <?=
            $form->field($model, 'timeZone')->widget(Select2::classname(), [
                'data' => Yii::$app->util->getTimeZoneList(),
                'language' => 'en',
                'options' => ['placeholder' => Yii::t('app', '- TimeZone -')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
            <?= $form->field($model, 'captcha')->widget(Captcha::className(), [
                'captchaAction' => 'user/captcha',
            ]) ?>
        </div>
    </div>

    <?= Html::submitButton(Yii::t('app', 'Register'), ['class' => 'btn btn-success']) ?>

    <?php ActiveForm::end(); ?>

</div>
