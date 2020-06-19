<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Category;
use frontend\models\Task;
use frontend\models\TaskCreate;
use frontend\models\TaskSearch;
use frontend\controllers\SecuredController;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\helpers\Json;

/**
 * TasksController implements the CRUD actions for Task model.
 */
class TasksController extends SecuredController
{
    /**
     * Lists all Task models.
     * @return mixed
     */
    public function actionIndex()
    {
        $periods = TaskSearch::periods();
        $allCategories = Category::getArray();
        $searchModel = new TaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'periods' => $periods,
            'allCategories' => $allCategories,
        ]);
    }

    /**
     * Displays a single Task model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Task model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TaskCreate();
        $allCategories = Category::getArray(false);

        if (Yii::$app->request->getIsPost()) {
            $model->load(Yii::$app->request->post());
            $model->uploadedFiles = UploadedFile::getInstances($model, 'uploadedFiles');
            $filenames = [];
            foreach ($model->uploadedFiles as $file) {
                $filenames[] = $file->name;
            }
            if (!empty($filenames)) {
                $model->files = Json::encode($filenames);
            }
            $model->setCustomerId(Yii::$app->user->identity->getId());
            if (!$model->city) {
                $model->setCityId(Yii::$app->user->identity->cityId);
            }
            //TODO заполнить lat и long
            $model->lat = 60;
            $model->lng = 60;
            if ($model->upload() && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'allCategories' => $allCategories,
        ]);
    }

    /**
     * Updates an existing Task model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Task model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Task model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Task the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Задание не найдено.');
    }
}
