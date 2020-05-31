<?php

namespace frontend\controllers;

use frontend\models\Category;
use frontend\models\Task;
use frontend\models\TaskFilterForm;
use Yii;
use yii\web\Controller;

/**
 * Browse Tasks Controller
 * 
 * Эта страница нужна для показа всех доступных на сайте заданий.
 */
class TasksController extends Controller
{
    /**
     * Displays tasks.
     *
     * @return void
     */
    public function actionIndex(array $categories = null)
    {
        $periods = Task::periods();
        $allCategories = Category::getArray();
        // Показываются только задания без привязыки к адресу, а также из города пользователя, либо из города, выбранного пользователем в текущей сессии.
        // TODO добавить проверку на выбранный город, заменить вывод города на район
        $request = Task::find()
        ->select(['tasks.id', 'tasks.name', 'tasks.dt_add', 'category' => 'categories.name', 'categories.icon', 'tasks.description', 'budget', 'cities.city', 'address'])
        ->where(['status' => 'new'])
        ->leftJoin('categories', 'tasks.category_id = categories.id')
        ->leftJoin('cities', 'tasks.city_id = cities.id');
        $model = new TaskFilterForm();
        if ($model->load(Yii::$app->request->get())) {
            if ($model->categories) {
                $query = ['or'];
                foreach ($model->categories as $category) {
                    $query[] = ['categories.icon'=>$category];
                }
                $request = $request->andWhere($query);
            }
            if ($model->hasNoReplies) {
                // добавляет к условию фильтрации показ заданий только без откликов исполнителей
                $request = $request->leftJoin('replies', 'replies.task_id = tasks.id')->andWhere(['IS', 'replies.id', null])->groupBy('tasks.id');
            }
            if ($model->isRemote) {
                // добавляет к условию фильтрации показ заданий без географической привязки
                $request = $request->andWhere(['is', 'address', null]);
            }
            if ($model->period) {
                // Выпадающий список «Период» добавляет к условию фильтрации диапазон времени, когда было создано задание
                switch ($model->period) {
                    case 'day':
                        $request = $request = $request->andFilterCompare('tasks.dt_add', date('Y-m-d', strtotime('-1 day')), '>');
                        break;
                    case 'week':
                        $request = $request = $request->andFilterCompare('tasks.dt_add', date('Y-m-d', strtotime('-1 week')), '>');
                        break;
                    case 'month':
                        $request = $request = $request->andFilterCompare('tasks.dt_add', date('Y-m-d', strtotime('-1 month')), '>');
                        break;
                }
            }
            if ($model->name) {
                // Поле «Поиск по названию» добавляет к условию фильтрации нестрогий поиск по совпадению в названии задания.
                $request = $request->andFilterWhere(['like', 'tasks.name', $model->name]);
            }
        }
        // На странице показывается максимум пять заданий.
        $tasks = $request->orderBy('tasks.dt_add DESC')->asArray()->limit(5)->all();
        return $this->render('@app/views/site/tasks', compact('tasks','allCategories','model','periods'));
    }
}
