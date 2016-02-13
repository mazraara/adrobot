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
                <?php
                if ($model->isNewRecord) {
                    echo $form->field($model, 'username');
                } else {
                    echo $form->field($model, 'username')->textInput(['readonly' => true]);
                }
                ?>
            </div>
            <div class="col-md-3">
                <?php
                if ($model->isNewRecord) {
                    echo $form->field($model, 'email');
                } else {
                    echo $form->field($model, 'email')->textInput(['readonly' => true]);
                }
                ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'formPassword')->passwordInput(['autocomplete' => 'off', 'class' => 'form-control password']) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'confPassword')->passwordInput(['autocomplete' => 'off', 'class' => 'form-control password']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'firstName') ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'lastName') ?>
            </div>
            <div class="col-md-3">
                <?=
                $form->field($model, 'dateOfBirth')->widget(DatePicker::classname(), [
                    'name' => 'date',
                    'value' => date('Y-M-d'),
                    'dateFormat' => 'yyyy-MM-dd',
                    'options' => [
                        'id' => 'startDate',
                        //'placeholder' => Yii::t('app', '"Due Date" From'),
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
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'gender')->radioList($model->getGenders(), array('template' => "<td>{input}</td><td>{label}</td>")); ?>
            </div>

        </div>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'streetAddress')->textInput(['maxlength' => true, 'id' => 'address', 'readonly' => true]) ?>
                <?= $form->field($model, 'about')->textArea(['rows' => '6', 'id' => 'about']) ?>
                <?= $form->field($model, 'geoLocaton')->hiddenInput(['id' => 'geoLocation'])->label(false); ?>
            </div>
        </div>
        <div class="row">
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
                $form->field($model, 'stateId')->widget(DepDrop::classname(), [
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
            <div class="col-md-3">
                <?=
                $form->field($model, 'cityId')->widget(DepDrop::classname(), [
                    'data' => isset($model) ? ArrayHelper::map(\app\models\City::find()->andWhere(['state_id' =>
                        $model->stateId])->asArray()->all(), 'id', 'name') : [],
                    'options' => ['placeholder' => 'Select ...'],
                    'type' => DepDrop::TYPE_SELECT2,
                    'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                    'pluginOptions' => [
                        'depends' => ['sat-id'],
                        'url' => Url::to(['/user/get-cities']),
                        'loadingText' => 'Loading child level 2 ...',
                    ]
                ]);
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <?=
                $form->field($model, 'roleName')->dropDownList(
                    ArrayHelper::map(Role::find()->where('name != :name', ['name' => Role::SUPER_ADMIN])->all(), 'name', 'name'), ['prompt' => Yii::t('app', '- Role -')]
                );
                ?>
            </div>
            <div class="col-md-3">
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
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'status')->dropDownList($model->getStatusesList(true)); ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-info' : 'btn btn-info']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
    <script></script>
<?php
//$locationUrl = Url::toRoute('get-location');
$mapUrl = Url::toRoute('map');
$script = <<< JS
$(document).ready(function() {
    tinymce.init({
        selector: '#about'
    });

    $(document).on('click', '#address', function(e) {
        util.openFancyboxIframe('{$mapUrl}', 700, 470);
        return false;
    });

});

JS;

$this->registerJs($script);
?>