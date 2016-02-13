<?php

namespace app\controllers;

use Yii;
use app\models\State;
use app\models\StateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StateController implements the CRUD actions for State model.
 */
class StateController extends BaseController
{
    public function allowed()
    {
        return [

        ];
    }

    public function behaviors()
    {
        return [

        ];
    }

    /**
     * Lists all State models.
     * @return mixed
     */
    public function actionIndex()
    {
        Yii::$app->appLog->writeLog('List States');

        $searchModel = new StateSearch();

        $params = (Yii::$app->request->isGet ? Yii::$app->request->queryParams : (Yii::$app->request->isPost ? Yii::$app->request->bodyParams : array()));
        Yii::$app->appLog->writeLog(print_r($params, true));

        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single State model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {

        Yii::$app->appLog->writeLog('View User', ['id' => $id]);
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new State model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $sucMsg = Yii::t('app', 'State created.');
        $errMsg = Yii::t('app', 'State create failed.');

        $model = new State();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', $sucMsg);
                return $this->redirect(['index']);
            } else
                Yii::$app->session->setFlash('error', $errMsg);

        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing State model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $sucMsg = Yii::t('app', 'State updated.');
        $errMsg = Yii::t('app', 'State update failed.');

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', $sucMsg);
                return $this->redirect(['index']);
            } else
                Yii::$app->session->setFlash('error', $errMsg);

        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing State model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $sucMsg = Yii::t('app', 'State was successfully deleted.');
        $errMsg = Yii::t('app', 'State could not be deleted.');

        $model = $this->findModel($id);
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', $sucMsg);
        } else {
            Yii::$app->session->setFlash('error', $errMsg);
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the State model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return State the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = State::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
