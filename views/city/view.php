<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Role;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->pageTitle = Yii::t('app', 'View City');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'States'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'View');
?>

<div class="city-view">

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            [
                'label' => $model->getAttributeLabel('state_id'),
                'value' => $model->state->name,
            ],
        ],
    ])
    ?>

</div>
