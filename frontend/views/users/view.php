<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model frontend\models\User */

// TODO Рядом располагается кнопка-иконка «закладка» для добавления пользователя в избранное. Клик по ней вызывает операцию добавления в избранное.

// TODO Под заголовком «Фото работ» показаны миниатюры из фотографий, которые были загружены пользователем в настройках. Клик по миниатюре открывает в новом окне полноразмерное фото.

$this->title = $model->name;
\yii\web\YiiAsset::register($this);

$rating = round($model->rating, 2);
$starsHtml = $categoriesHtml = '';
for ($i = 1; $i <= 5; $i++) {
    $starsHtml .= (($i <= $rating) ? '<span>' : '<span class="star-disabled">') . '</span>';
}
$chosenCategories = explode(', ', $model->profile->categories);
foreach ($chosenCategories as $category) {
    $categoriesHtml .= Html::a($allCategories[$category], ['tasks/', 'categories[]' => $category], ['class' => 'link-regular']);
}
?>
<section class="content-view">
    <div class="user__card-wrapper">
        <div class="user__card">
        <?= Html::img(Url::to('@web/img/man-hat.png'),['width'=>120,'height'=>120, 'alt'=>'Аватар пользователя']) ?>
                <div class="content-view__headline">
                <h1><?= Html::encode($this->title) ?></h1>
                    <p>Россия, <?= $model->city->city ?><?php if ($model->profile->bd) {
                        echo ', '.Yii::$app->i18n->format('{n, plural, one{# год} few{# года} many{# лет} other{# лет}}', ['n' => date_create($model->profile->bd)->diff(date_create('today'))->y], 'ru_RU');
                        } ?></p>
                <div class="profile-mini__name five-stars__rate">
                    <?= $starsHtml ?>
                    <b><?= $rating ? $rating : '' ?></b>
                </div>
                <b class="done-task">Выполнил <?= Yii::$app->i18n->format('{n, plural, one{# заказ} few{# заказа} many{# заказов} other{# заказов}}', ['n' => $model->tasksAssignedCount], 'ru_RU') ?></b><b class="done-review">Получил <?= Yii::$app->i18n->format('{n, plural, one{# отзыв} few{# отзыва} many{# отзывов} other{# отзывов}}', ['n' => $model->opinionsGotCount], 'ru_RU') ?></b>
                </div>
            <div class="content-view__headline user__card-bookmark<?php if ($model->bookmarkedByCurrentUser) {echo ' user__card-bookmark--current';} ?>">
                <span>Был на сайте <?= Yii::$app->formatter->format($model->lastActivityTime, 'relativeTime') ?></span>
                    <a href="#"><b></b></a>
            </div>
        </div>
        <div class="content-view__description">
            <p><?= $model->profile->about ?></p>
        </div>
        <div class="user__card-general-information">
            <div class="user__card-info">
                <h3 class="content-view__h3">Специализации</h3>
                <div class="link-specialization">
                    <?= $categoriesHtml ?>
                </div>
                <h3 class="content-view__h3">Контакты</h3>
                <div class="user__card-link">
                    <?= Html::a($model->profile->phone, 'tel:'.str_replace(['+', ' ', ' ', '(', ')', '-'], ['%2B'], $model->profile->phone), ['class' => 'user__card-link--tel', 'link-regular']) ?>
                    <?= Html::mailto($model->email, $model->email, ['class' => 'user__card-link--email link-regular']) ?>
                    <?= Html::a($model->profile->skype, 'skype:'.$model->profile->skype.'?call', ['class' => 'user__card-link--skype link-regular']) ?>
                </div>
                </div>
            <div class="user__card-photo">
                <h3 class="content-view__h3">Фото работ</h3>
                <a href="#"><img src="./img/rome-photo.jpg" width="85" height="86" alt="Фото работы"></a>
                <a href="#"><img src="./img/smartphone-photo.png" width="85" height="86" alt="Фото работы"></a>
                <a href="#"><img src="./img/dotonbori-photo.png" width="85" height="86" alt="Фото работы"></a>
                </div>
        </div>
    </div>
    <?php if ($model->opinionsGot) {
        echo '<div class="content-view__feedback"><h2>Отзывы<span>('.$model->opinionsGotCount.')</span></h2>
        <div class="content-view__feedback-wrapper reviews-wrapper">';
        foreach ($model->opinionsGot as $opinion) {
            // $rating = round($reply->executor->rating, 2);
            // $stars = '';
            // for ($i = 1; $i <= 5; $i++) {
            //     $stars .= (($i <= $rating) ? '<span>' : '<span class="star-disabled">') . '</span>';
            // }
            echo '<div class="feedback-card__reviews">
            <p class="link-task link">Задание '.Html::a($opinion->task->name, ['tasks/view', 'id' => $opinion->task->id], ['class' => 'link-regular']).'</p>
            <div class="card__review">'.Html::a(Html::img(Url::to('@web/img/man-glasses.jpg'),['width'=>55,'height'=>55]), ['users/view', 'id' => $opinion->customer->id], ['class' => 'link-regular']).'
                <div class="feedback-card__reviews-content">
                    <p class="link-name link">'.Html::a($opinion->customer->name, ['users/view', 'id' => $opinion->customer->id], ['class' => 'link-regular']).'</p>
                    <p class="review-text">'.$opinion->description.'</p>
                </div>
                <div class="card__review-rate">
                    <p class="'.($opinion->rate>3?'five':'three').'-rate big-rate">'.$opinion->rate.'<span></span></p>
                </div>
            </div>
        </div>';
        }
        echo '</div></div>';
    }
    ?>
</section>
<section class="connect-desk">
    <div class="connect-desk__chat">

    </div>
</section>
