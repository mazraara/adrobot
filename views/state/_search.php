<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use yii\jui\DatePicker;
use yii\helpers\ArrayHelper;
use app\models\Role;
use app\models\Country;
use kartik\select2\Select2;


/* @var $this yii\web\View */
/* @var $model app\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="state-search">
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
        <div class="col-md-4">
            <?=
            $form->field($model, 'country_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(Country::find()->asArray()->all(), 'id', 'name'),
                'options' => ['placeholder' => Yii::t('app', '- Country -'), 'id' => 'cat-id'],
                'pluginOptions' => [
                    'allowClear' => true
                ],

            ])->label(false);
            ?>
        </div>
        <div class="col-md-2">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-info']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
    <?php Pjax::end(); ?>

</div>