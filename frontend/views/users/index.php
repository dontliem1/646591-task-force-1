<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Исполнители';
?>
<h1 class="visually-hidden"><?= Html::encode($this->title) ?></h1>
<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'layout' => "<div class='user__search-link'><p>Сортировать по:</p>{sorter}</div>\n{items}\n<div class='new-task__pagination'>{pager}</div>",
    'itemOptions' => ['class' => 'content-view__feedback-card user__search-wrapper'],
    'options' => [
        'tag'=>'section',
        'class'=>'user__search'
    ],
    'viewParams' => ['allCategories' => $allCategories],
    'itemView' => '_item',
    'sorter' => [
        'linkOptions' => [
            'class' => 'link-regular',
        ],
        'options' => [
            'class' => 'user__search-list',
            'item' => function($item, $index) {
                if (strpos($item,'dtAdd')) {return false;}
                $currentSort = Yii::$app->request->get('sort');
                $class = 'user__search-item';
                if ($currentSort && strpos($item,ltrim($currentSort, '-'))) {
                    $class .= ' user__search-item--current';
                }
                return "<li class='$class' data-index='$index'>$item</li>";
            }
        ],
    ],
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
        <?php echo $this->render('_search', ['model' => $searchModel, 'allCategories' => $allCategories]); ?>
    </div>
</section>