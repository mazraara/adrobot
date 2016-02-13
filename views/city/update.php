<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->pageTitle = Yii::t('app', 'Update City');
$this->pageTitleDescription = Yii::t('app', 'Update City');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<div class="city-update">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
