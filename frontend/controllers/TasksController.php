<?php

namespace frontend\controllers;

use frontend\models\Task;
use yii\web\Controller;

/**
 * Browse Tasks Controller
 */
class TasksController extends Controller
{
    /**
     * Displays tasks.
     *
     * @return void
     */
    public function actionIndex()
    {
        $tasks = Task::find()->where(['status' => 'new'])->joinWith(['category', 'city'])->orderBy('dt_add DESC')->all();
        return $this->render('@app/views/site/tasks', compact("tasks"));
    }
}
