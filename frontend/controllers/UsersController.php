<?php

namespace frontend\controllers;

use frontend\models\Category;
use frontend\models\User;
use frontend\models\UserFilterForm;
use Yii;
use yii\web\Controller;

/**
 * Browse Users Controller
 * 
 * Страница для показа всех зарегистрированных на сайте исполнителей.
 */
class UsersController extends Controller
{
    // По умолчанию выбран критерий «дата регистрации».
    const DEFAULT_SORTING = 'dt_add';

    /**
     * Displays users.
     *
     * @return void
     */
    public function actionIndex($sort = self::DEFAULT_SORTING)
    {
        $sortings = User::sortings();
        if (!key_exists($sort, $sortings)) {
            $sort = self::DEFAULT_SORTING;
        }
        $categories = Category::getArray();
        $request = User::find()
            ->select(['users.id', 'users.name', 'users.dt_add', 'categories', 'views', 'about', 'last_activity_time', 'tasks' => 'COUNT(tasks.id)', 'opinions' => 'COUNT(opinions.id)', 'rating' => 'AVG(opinions.rate)'])
            // Исполнителями считаются пользователи, отметившие хотя бы одну категорию у себя в профиле.
            ->where(['IS NOT', 'categories', null])
            ->leftJoin('profiles', 'users.id = profiles.user_id')
            ->leftJoin('tasks', 'users.id = tasks.executor_id')
            ->leftJoin('opinions', 'users.id = opinions.executor_id')
            ->groupBy('users.id');
        $model = new UserFilterForm();
        if ($model->load(Yii::$app->request->get())) {
            if ($model->name) {
                // Поле «Поиск по имени» сбрасывает все выбранные фильтры и ищет пользователя с нестрогим совпадением по его имени.
                $request = $request->andFilterWhere(['like', 'users.name', $model->name]);
                $model->categories = $model->isFree = $model->isOnline = $model->hasOpinions = $model->hasOpinions = $model->isBookmarked = null;
            }
            if ($model->categories) {
                foreach ($model->categories as $category) {
                    $request = $request->andWhere('MATCH(categories) AGAINST (:category)', [':category' => $category]);
                }
            }
            if ($model->isFree) {
                // добавляет к условию фильтрации показ исполнителей, для которых сейчас нет назначенных активных заданий
                $request = $request->andWhere(['<>', 'tasks.status', 'active'])->orWhere(['is', 'tasks.id', null]);
            }
            if ($model->isOnline) {
                // добавляет к условию фильтрации показ исполнителей, время последней активности которых было не больше получаса назад
                $request = $request->andFilterCompare('users.last_activity_time', date('Y-m-d H:i:s', strtotime('-30 mins')), '>');
            }
            if ($model->hasOpinions) {
                // добавляет к условию фильтрации показ исполнителей с отзывами
                $request = $request->andWhere(['IS NOT', 'opinions.id', null]);
            }
            if ($model->isBookmarked) {
                // добавляет к условию фильтрации показ пользователей, которые были добавлены в избранное
                //TODO подставить текущего пользователя
                $current_user = 1;
                $request = $request->leftJoin('bookmarks', 'bookmarked_id = users.id')->andFilterCompare('bookmarks.user_id', $current_user);
            }
        }
        // Список исполнителей всегда отсортирован по одному критерию от большего к меньшему.
        // На странице показывается максимум пять исполнителей.
        $users = $request->orderBy([$sort => SORT_DESC])->asArray()->limit(5)->all();
        return $this->render('@app/views/site/users', compact('sortings', 'categories', 'users', 'model'));
    }
}
