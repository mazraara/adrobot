<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Role;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<?php
Pjax::begin([
    'id' => 'dataPjax',
    'timeout' => false,
    'enablePushState' => false,
    'clientOptions' => ['method' => 'get'],
]);
?>
<?=
GridView::widget([
    'id' => 'dataGrid',
    'dataProvider' => $dataProvider,
//    'filterModel' => $model,
    'tableOptions' => ['class' => 'table table-striped'],
    'columns' => [
        'id',
        'name',
        [
            'attribute' => 'country_id',
            'value' => 'country.name'
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'contentOptions' => ['style' => 'text-align: right'],
            'template' => '{view} {update} {delete}',
            'buttons' => [
                'view' => function ($url) {
                    return Yii::$app->user->can('State.View') ? Html::a('<span class="glyphicon
                    glyphicon-eye-open"></span>', $url) : '';
                },
                'update' => function ($url, $model, $key) {
                    $return = '';
//                    if (Yii::$app->user->identity->isSuperadmin) {
//                        $return = Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['class' => 'edit']);
//                    }
                    return $return;
                },
                'delete' => function ($url, $model, $key) {
                    $return = '';
//                    if (Yii::$app->user->identity->isSuperadmin) {
//                        $return = Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
//                            'class' => 'delete',
//                            'data' => [
//                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
//                                'method' => 'post',
//                            ]
//                        ]);
//                    }
                    return $return;
                },
            ],
        ]
    ]
]);
?>
<?php Pjax::end(); ?>

