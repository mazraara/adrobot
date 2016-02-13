<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->pageTitle = Yii::t('app', 'Cities');
$this->pageTitleDescription = Yii::t('app', 'Listing all Cities');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'List');
?>
<?php
$this->registerJs(
    '$("document").ready(function(){
        $("#searchPjax").on("pjax:end", function() {
            $.pjax.reload({container: "#dataPjax"});  // Reload GridView
        });
    });'
);
?>
<div class="user-index">

    <?php if (Yii::$app->user->can('City.Create')): ?>
        <p><?= Html::a(Yii::t('app', 'Create City'), ['create'], ['class' => 'btn btn-info']) ?></p>
    <?php endif ?>

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php echo $this->render('_grid', ['model' => $searchModel, 'dataProvider' => $dataProvider]); ?>
</div>
