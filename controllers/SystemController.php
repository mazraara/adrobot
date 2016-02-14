<?php

namespace app\controllers;

use Yii;

class SystemController extends BaseController
{
    public function allowed()
    {
        return [
            'System.FlushCache',
            'System.ClearAssets',
            'System.Setting',
        ];
    }

    public function behaviors()
    {
        return [

        ];
    }

    public function actionSetting()
    {
        return $this->render('index');
    }

    public function actionFlushCache()
    {
        Yii::$app->cache->flush();
        Yii::$app->session->setFlash('success', Yii::t('app', 'Cache flushed'));
//        return $this->goBack();
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionClearAssets()
    {
        foreach (glob(Yii::$app->assetManager->basePath . DIRECTORY_SEPARATOR . '*') as $asset) {
            if (is_link($asset)) {
                unlink($asset);
            } elseif (is_dir($asset)) {
                $this->deleteDir($asset);
            } else {
                unlink($asset);
            }
        }
        Yii::$app->session->setFlash('success', Yii::t('app', 'Assets cleared'));
//        return $this->goBack();
        return $this->redirect(Yii::$app->request->referrer);
    }

    private function deleteDir($directory)
    {
        $iterator = new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        return rmdir($directory);
    }
}
