<?php

namespace frontend\controllers;

use frontend\models\Category;
use frontend\models\User;
use yii\web\Controller;

/**
 * Browse Users Controller
 */
class UsersController extends Controller
{
    /**
     * Displays users.
     *
     * @return void
     */
    public function actionIndex()
    {
        $categoriesArray = Category::find()->asArray(true)->all();
        $categories = [];
        foreach ($categoriesArray as $category) {
            $categories[$category['icon']] = $category['name'];
        }
        $users = User::find()->joinWith(['profiles', 'replies', 'opinionsGot', 'tasksAssigned'])->where(['IS NOT', 'categories', null])->orderBy('dt_add DESC')->all();
        return $this->render('@app/views/site/users', compact('categories', 'users'));
    }
}
