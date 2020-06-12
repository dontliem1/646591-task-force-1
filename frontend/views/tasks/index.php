<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\TaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Новые задания';

echo ListView::widget([
    'dataProvider' => $dataProvider,
    'layout' => "<div class='new-task__wrapper'><h1>{$this->title}</h1>{items}</div>\n<div class='new-task__pagination'>{pager}</div>",
    'itemOptions' => ['class' => 'new-task__card'],
    'itemView' => function ($model, $key, $index, $widget) {
        //TODO Название района следует получать из координат, указанных в задании, через geocoder API
        return '<div class="new-task__title">'.Html::a('<h2>'.$model->name.'</h2>', ['view', 'id' => $model->id], ['class' => ['link-regular']]).Html::a('<p>'.$model->category->name.'</p>', ['/tasks', 'categories' => [$model->category->icon]], ['class' => ['new-task__type','link-regular']]).'</div>
            <div class="new-task__icon new-task__icon--'.$model->category->icon.'"></div>
            <p class="new-task_description">'.$model->description.'</p>
            <b class="new-task__price new-task__price--'.$model->category->icon.'">'.$model->budget.'<b> ₽</b></b>
            <p class="new-task__place">'.$model->city->city.'</p>
            <span class="new-task__time">'.Yii::$app->formatter->format($model->dtAdd, 'relativeTime').'</span>';
    },
    'options' => ['class' => 'new-task', 'tag' => 'section'],
    'pager' => [
        'options' => [
            'class' => 'new-task__pagination-list',
        ],
        'pageCssClass' => 'pagination__item',
        'prevPageCssClass' => 'pagination__item',
        'nextPageCssClass' => 'pagination__item',
        'activePageCssClass' => 'pagination__item--current',
        'nextPageLabel' => '',
        'prevPageLabel' => '',
    ],
]) ?>
<section class="search-task">
    <div class="search-task__wrapper">
    <?php echo $this->render('_search', ['model' => $searchModel, 'periods' => $periods, 'allCategories' => $allCategories]); ?>
    </div>
</section>