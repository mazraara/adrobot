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
            <?= $form->field($model, 'name')->textInput(['placeholder' => $model->getAttributeLabel('name')])->label(false) ?>
        </div>
        <div class="col-md-3">
            <?=
            // Top most parent
            $form->field($model, 'countryId')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(Country::find()->asArray()->all(), 'id', 'name'),
                'options' => ['placeholder' => Yii::t('app', '- Country -'), 'id' => 'cat-id'],
                'pluginOptions' => [
                    'allowClear' => true
                ],

            ]);
            ?>
        </div>
        <div class="col-md-3">
            <?=
            $form->field($model, 'state_id')->widget(DepDrop::classname(), [
                'data' => isset($model) ? ArrayHelper::map(\app\models\State::find()->andWhere(['country_id' =>
                    $model->countryId])->asArray()->all(), 'id', 'name') : [],
                'options' => ['placeholder' => 'Select ...', 'id' => 'sat-id'],
                'type' => DepDrop::TYPE_SELECT2,
                'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                'pluginOptions' => [
                    'depends' => ['cat-id'],
                    'url' => Url::to(['/user/get-states']),
                    'loadingText' => 'Loading child level 1 ...',
                ]
            ]);
            ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-info' : 'btn btn-info']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>