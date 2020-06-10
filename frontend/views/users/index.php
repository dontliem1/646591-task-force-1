<?php

use frontend\models\Category;
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
    'itemOptions' => ['class' => ['content-view__feedback-card', 'user__search-wrapper']],
    'options' => [
        'tag'=>'section',
        'class'=>'user__search'
    ],
    'itemView' => function ($model, $key, $index, $widget) {
        $allCategories = Category::getArray();
        $rating = round($model->rating, 2);
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            $stars .= (($i <= $rating) ? '<span>' : '<span class="star-disabled">') . '</span>';
        }
        $chosenCategories = explode(', ', $model->profile->categories);
        $categoriesHtml = '';
        foreach ($chosenCategories as $category) {
            $categoriesHtml .= Html::a($allCategories[$category], ['index', 'categories[]' => $category], ['class' => ['link-regular']]);
        }
        return '<div class="content-view__feedback-card user__search-wrapper">
        <div class="feedback-card__top">
            <div class="user__search-icon">'.Html::a(Html::img(Url::to('@web/img/man-glasses.jpg'),['width'=>65,'height'=>65]), ['view', 'id' => $model->id]).'
                <span>'.Yii::$app->i18n->format('{n, plural, one{# задание} few{# задания} many{# заданий} other{# заданий}}', ['n' => $model->tasks], 'ru_RU').'</span>
                <span>'.Yii::$app->i18n->format('{n, plural, one{# отзыв} few{# отзыва} many{# отзывов} other{# отзывов}}', ['n' => $model->opinions], 'ru_RU').'</span>
            </div>
            <div class="feedback-card__top--name user__search-card">
                <p class="link-name">'.Html::a($model->name, ['view', 'id' => $model->id], ['class' => ['link-regular']]).'</p>'.$stars.'<b>'.($rating ? $rating : '').'</b>
                <p class="user__search-content">'.$model->profile->about.'</p>
            </div>
            <span class="new-task__time">Был на сайте ' . Yii::$app->formatter->format($model->last_activity_time, 'relativeTime').'
            </span>
        </div>
        <div class="link-specialization user__search-link--bottom">'.$categoriesHtml.'</div>
    </div>';
    },
    'sorter' => [
        'linkOptions' => [
            'class' => 'link-regular',
        ],
        'options' => [
            'class' => 'user__search-list',
            'item' => function($item, $index) {
                if (strpos($item,'dt_add')) {return false;}
                $currentSort = Yii::$app->request->get('sort');
                $class = 'user__search-item';
                if ($currentSort && strpos($item,ltrim($currentSort, '-'))) {
                    $class .= ' user__search-item--current';
                }
                return "<li class='$class'>$item</li>";
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