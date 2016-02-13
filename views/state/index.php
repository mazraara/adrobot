<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->pageTitle = Yii::t('app', 'States');
$this->pageTitleDescription = Yii::t('app', 'Listing all states');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'States'), 'url' => ['index']];
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

    <?php if (Yii::$app->user->can('State.Create')): ?>
        <p><?= Html::a(Yii::t('app', 'Create State'), ['create'], ['class' => 'btn btn-info']) ?></p>
    <?php endif ?>

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php echo $this->render('_grid', ['model' => $searchModel, 'dataProvider' => $dataProvider]); ?>
</div>
