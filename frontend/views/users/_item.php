<?php

use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\UserSearch */

$rating = round($model->rating, 2);
$starsHtml = $categoriesHtml = '';
for ($i = 1; $i <= 5; $i++) {
    $starsHtml .= (($i <= $rating) ? '<span>' : '<span class="star-disabled">') . '</span>';
}
$chosenCategories = explode(', ', $model->profile->categories);
foreach ($chosenCategories as $category) {
    $categoriesHtml .= Html::a($allCategories[$category], ['index', 'categories[]' => $category], ['class' => 'link-regular']);
}
echo '<div class="content-view__feedback-card user__search-wrapper">
    <div class="feedback-card__top">
        <div class="user__search-icon">'.Html::a(Html::img(Url::to('@web/img/man-glasses.jpg'),['width'=>65,'height'=>65]), ['view', 'id' => $model->id]).'
            <span>'.Yii::$app->i18n->format('{n, plural, one{# задание} few{# задания} many{# заданий} other{# заданий}}', ['n' => $model->tasksAssignedCount], 'ru_RU').'</span>
            <span>'.Yii::$app->i18n->format('{n, plural, one{# отзыв} few{# отзыва} many{# отзывов} other{# отзывов}}', ['n' => $model->opinionsGotCount], 'ru_RU').'</span>
        </div>
        <div class="feedback-card__top--name user__search-card">
            <p class="link-name">'.Html::a($model->name, ['view', 'id' => $model->id], ['class' => 'link-regular']).'</p>'.$starsHtml.'<b>'.($rating ? $rating : '').'</b>
            <p class="user__search-content">'.$model->profile->about.'</p>
        </div>
        <span class="new-task__time">Был на сайте '.Yii::$app->formatter->format($model->lastActivityTime, 'relativeTime').'
        </span>
    </div>
    <div class="link-specialization user__search-link--bottom">'.$categoriesHtml.'</div>
</div>';