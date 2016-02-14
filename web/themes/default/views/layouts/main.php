<?php

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
$title = '' == $this->title ? Yii::$app->params['productName'] : Yii::$app->params['productName'] . ' - ' . $this->title;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->params['productName'],
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    if (!Yii::$app->getUser()->isGuest):
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav'],
            'activateItems' => true,
            'activateParents' => true,
            'items' => [
                ['label' => Yii::t('app', 'Adverts'), 'url' => ['/hunt/index'], 'visible' => Yii::$app->user->can('Hunt.Index')],
                ['label' => Yii::t('app', 'Locations'),
                    'visible' => Yii::$app->user->canList(['State.Index', 'City.Index']),
                    'items' => [
                        ['label' => Yii::t('app', 'States'), 'url' => ['/state/index'], 'visible' => Yii::$app->user->can('Permission.Index')],
                        ['label' => Yii::t('app', 'Cities'), 'url' => ['/city/index'], 'visible' => Yii::$app->user->can('Role.Index')],
                    ],
                ],
                [
                    'label' => Yii::t('app', 'System'),
                    'visible' => Yii::$app->user->canList(['Permission.Index', 'Role.Index', 'User.Index', 'AuditTrail.Index']),
                    'items' => [
                        ['label' => Yii::t('app', 'Permissions'), 'url' => ['/permission/index'], 'visible' => Yii::$app->user->can('Permission.Index')],
                        ['label' => Yii::t('app', 'Roles'), 'url' => ['/role/index'], 'visible' => Yii::$app->user->can('Role.Index')],
                        ['label' => Yii::t('app', 'System Users'), 'url' => ['/user/index'], 'visible' => Yii::$app->user->can('User.Index')],
                        ['label' => Yii::t('app', 'Portal Settings'), 'url' => ['/system/setting'], 'visible' => Yii::$app->user->can('System.Setting')],
                        ['label' => Yii::t('app', 'Flush Cache'), 'url' => ['/system/flush-cache'], 'visible' => Yii::$app->user->can('System.FlushCache')],
                        ['label' => Yii::t('app', 'Clear Asset'), 'url' => ['/system/clear-assets'], 'visible' => Yii::$app->user->can('System.ClearAssets')],
                    ],
                ],
                ['label' => Yii::t('app', 'Setting'), 'url' => ['/setting/index'], 'visible' => Yii::$app->user->can('Hunt.Index')],
            ],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'activateItems' => true,
            'activateParents' => true,
            'items' => [
                [
                    'label' => Yii::$app->user->identity->firstName . ' ' . Yii::$app->user->identity->lastName,
                    'items' => [
                        ['label' => Yii::t('app', 'My Account'), 'url' => ['/user/my-account'], 'visible' => Yii::$app->user->can('User.MyAccount')],
                        ['label' => Yii::t('app', 'Change Password'), 'url' => ['/user/change-password'], 'visible' => Yii::$app->user->can('User.ChangePassword')],
                        ['label' => Yii::t('app', 'Logout'), 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post']],
                    ],
                ],
            ],
        ]);
    endif;
    NavBar::end();
    ?>

    <div class="container page">
        <div class="card-breadcrumb">
            <?=
            Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]);
            ?>
        </div>
        <div class="card">
            <div id="statusMsg"></div>
            <?php
            foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
                if ($key == 'error') {
                    $key = 'danger';
                }
                echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
            }
            ?>

            <?php
            if ('' != $this->tabMenu) {
                echo $this->render($this->tabMenu, []);
            }
            ?>

            <?php if (null != $this->pageTitle): ?>
                <div class="card-header">
                    <h2><?= $this->pageTitle ?>
                        <?php if (null != $this->pageTitleDescription): ?>
                            <small><?= $this->pageTitleDescription ?></small>
                        <?php endif; ?>
                    </h2>
                </div>
            <?php endif; ?>
            <?= $content ?>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p style="text-align:center">&copy; <?= Yii::t('app', Yii::$app->params['copyright']) ?> <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
