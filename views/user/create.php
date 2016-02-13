<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->pageTitle = Yii::t('app', 'Create User');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>
<style>
    #user-gender label::after {
        content: "\00a0" !important;
    }
</style>
<div class="user-create">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
