<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use yii\jui\DatePicker;
use yii\helpers\ArrayHelper;
use app\models\Role;
use app\models\Country;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="city-search">
    <?php Pjax::begin(['id' => 'searchPjax']); ?>
    <?php
    $form = ActiveForm::begin([
        'id' => 'searchForm',
        'action' => ['index'],
        'method' => 'get',
        'options' => ['data-pjax' => true],
    ]);
    ?>

    <div class="row">
        <div class="col-md-3">
            <?=
            // Top most parent
            $form->field($model, 'countryId')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(Country::find()->asArray()->all(), 'id', 'name'),
                'options' => ['placeholder' => Yii::t('app', '- Country -'), 'id' => 'cat-id'],
                'pluginEvents' => [
                    "change" => "function() { if($(this).val() == '') {
                    $('#txt-name').val('');
                    $('#txt-name').attr(\"disabled\", \"disabled\");
                    }
                    else {
                     $('#txt-name').prop(\"disabled\", false);
                     }
                    }",

                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],

            ])->label(false);
            ?>
        </div>
        <div class="col-md-3">
            <?=
            $form->field($model, 'state_id')->widget(DepDrop::classname(), [
                'data' => isset($model) ? ArrayHelper::map(\app\models\State::find()->andWhere(['country_id' =>
                    $model->countryId])->asArray()->all(), 'id', 'name') : [],
                'options' => ['placeholder' => 'Select', 'id' => 'sat-id'],
                'type' => DepDrop::TYPE_SELECT2,
                'select2Options' => ['pluginOptions' => ['allowClear' => true, 'placeholder'=>'Select',]],
                'pluginOptions' => [
                    'depends' => ['cat-id'],
                    'url' => Url::to(['/user/get-states']),
                    'placeholder'=>'- State -',
                    'loadingText' => 'Loading child level 1 ...',
                ]
            ])->label(false);
            ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'name')->textInput(['disabled' => is_null($model->countryId) ? true : false, 'placeholder' => $model->getAttributeLabel('name'), 'id' => 'txt-name'])->label(false) ?>
        </div>
        <div class="col-md-2">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-info']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
    <?php Pjax::end(); ?>

</div>