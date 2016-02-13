<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Role;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->pageTitle = Yii::t('app', 'View State');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'States'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'View');
?>

<div class="state-view">

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            [
                'label' => $model->getAttributeLabel('country_id'),
                'value' => $model->country->name,
            ],
        ],
    ])
    ?>

</div>
