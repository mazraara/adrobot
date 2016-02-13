<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->pageTitle = Yii::t('app', 'Update State');
$this->pageTitleDescription = Yii::t('app', 'Update State');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'States'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<div class="state-update">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
