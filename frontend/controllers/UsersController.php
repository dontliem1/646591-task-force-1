<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Category;
use frontend\models\City;
use frontend\models\UserSearch;
use frontend\models\User;
use frontend\controllers\SecuredController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * UsersController implements the CRUD actions for User model.
 */
class UsersController extends SecuredController
{
    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $allCategories = Category::getArray();
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'allCategories' => $allCategories,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * Если этот пользователь не является исполнителем, то страница должна быть недоступна: вместо неё показывать ошибку 404.
     * 
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $allCategories = Category::getArray();
        $model = $this->findModel($id);
        if (!$model->profile->categories) {
            throw new NotFoundHttpException('Пользователь не является исполнителем');
        }
        return $this->render('view', [
            'model' => $model,
            'allCategories' => $allCategories,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the home page.
     * @return mixed
     */
    public function actionCreate()
    {
        $allCities = City::getArray();
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->dt_add = date('Y-m-d');
            if ($model->save()) {
                return $this->goHome();
            }
        }

        return $this->render('create', [
            'model' => $model,
            'allCities' => $allCities,
        ]);
    }

    /**
     * Updates an existing User model.
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
     * Deletes an existing User model.
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Пользователь не найден.');
    }
}
