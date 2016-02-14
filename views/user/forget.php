<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */
$this->title = 'Reset Password';
?>
    <div class="login-title"><?php echo Yii::t('app', '{name}', ['name' => 'Reset Password']); ?></div>

<?php $form = ActiveForm::begin([
    'id' => 'reset-form',
    'options' => [],
    'fieldConfig' => [],
]); ?>

<?= $form->field($model, 'email') ?>

<?php /*$form->field($model, 'captcha')->widget(Captcha::className(), [
		//'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-3">{input}</div></div>',
		'captchaAction' => 'site/captcha',
	])*/ ?>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-info btn-block', 'name' => 'login-button']) ?>
        <?= Html::a(Yii::t('app', 'Cancel'), ['site/login'], ['class' => 'btn btn-success btn-block']) ?>

    </div>

<?php ActiveForm::end(); ?>