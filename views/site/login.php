<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */
$this->title = 'Login';
?>
    <div class="login-title"><?php echo Yii::t('app', '{name}', ['name' => Yii::$app->params['productName']]); ?></div>

<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'options' => [],
    'fieldConfig' => [],
]); ?>

<?= $form->field($model, 'username') ?>

<?= $form->field($model, 'password')->passwordInput() ?>

<?php /*$form->field($model, 'captcha')->widget(Captcha::className(), [
		//'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-3">{input}</div></div>',
		'captchaAction' => 'site/captcha',
	])*/ ?>

    <div class="form-group">
        <?= Html::submitButton('Login', ['class' => 'btn btn-info btn-block', 'name' => 'login-button']) ?>
        <?= Html::a(Yii::t('app', 'Register'), ['user/register'], ['class' => 'btn btn-success btn-block']) ?>
        <?= Html::a(Yii::t('app', 'Forgot Password'), ['user/forget-password'], ['class' => 'btn btn-success
        btn-block']) ?>
    </div>

<?php ActiveForm::end(); ?>