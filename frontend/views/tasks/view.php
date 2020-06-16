<?php

use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model frontend\models\Task */

$this->title = $model->name;
\yii\web\YiiAsset::register($this);
?>
<section class="content-view">
<div class="content-view__card">
    <div class="content-view__card-wrapper">
        <div class="content-view__header">
            <div class="content-view__headline">
                <h1><?= Html::encode($this->title) ?></h1>
                <span>Размещено в категории
                    <?= Html::a($model->category->name, ['tasks/', 'categories' => [$model->category->icon]], ['class' => ['link-regular']]) ?>
                    <?= Yii::$app->formatter->format($model->dtAdd, 'relativeTime') ?></span>
            </div>
            <b class="new-task__price new-task__price--<?= $model->category->icon ?> content-view-price"><?= $model->budget ?><b> ₽</b></b>
            <div class="new-task__icon new-task__icon--<?= $model->category->icon ?> content-view-icon"></div>
        </div>
        <div class="content-view__description">
            <h3 class="content-view__h3">Общее описание</h3>
            <p><?= $model->description ?></p>
        </div>
        <div class="content-view__attach">
            <h3 class="content-view__h3">Вложения</h3>
            <!-- TODO реализовать вложения -->
            <a href="#">my_picture.jpeg</a>
            <a href="#">agreement.docx</a>
        </div>
        <div class="content-view__location">
            <h3 class="content-view__h3">Расположение</h3>
            <!-- TODO подтянуть расположение -->
            <div class="content-view__location-wrapper">
                <div class="content-view__map">
                    <a href="#"><img src="<?= Url::to('@web/img/map.jpg') ?>" width="361" height="292"
                                        alt="Москва, Новый арбат, 23 к. 1"></a>
                </div>
                <div class="content-view__address">
                    <span class="address__town"><?= $model->city->city ?></span><br>
                    <span>Новый арбат, 23 к. 1</span>
                    <p>Вход под арку, код домофона 1122</p>
                </div>
            </div>
        </div>
    </div>
    <div class="content-view__action-buttons">
            <button class=" button button__big-color response-button open-modal"
                    type="button" data-for="response-form">Откликнуться</button>
            <button class="button button__big-color refusal-button open-modal"
                    type="button" data-for="refuse-form">Отказаться</button>
        <button class="button button__big-color request-button open-modal"
                type="button" data-for="complete-form">Завершить</button>
    </div>
</div>
<?php if ($model->replies) {
    echo '<div class="content-view__feedback"><h2>Отклики <span>('.count($model->replies).')</span></h2><div class="content-view__feedback-wrapper">';
    foreach ($model->replies as $reply) {
        $rating = round($reply->executor->rating, 2);
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            $stars .= (($i <= $rating) ? '<span>' : '<span class="star-disabled">') . '</span>';
        }
        echo '<div class="content-view__feedback-card">
        <div class="feedback-card__top">'.Html::a(Html::img(Url::to('@web/img/man-glasses.jpg'),['width'=>55,'height'=>55]), ['users/view', 'id' => $reply->executor->id], ['class' => 'link-regular']).'
            <div class="feedback-card__top--name">
                <p class="link-name">'.Html::a($reply->executor->name, ['users/view', 'id' => $reply->executor->id], ['class' => 'link-regular']).'</p>'.$stars.'<b>'.($rating ? $rating : '').'</b>
            </div>
            <span class="new-task__time">'.Yii::$app->formatter->format($reply->dtAdd, 'relativeTime').'</span>
        </div>
        <div class="feedback-card__content">
            <p>'.$reply->description.'</p>
            <span>'.$reply->offer.' ₽</span>
        </div>
        <div class="feedback-card__actions">
            <a class="button__small-color request-button button"
                    type="button">Подтвердить</a>
            <a class="button__small-color refusal-button button"
                    type="button">Отказать</a>
        </div>
    </div>';
    }
    echo '</div></div>';
} ?>
</section>
<section class="connect-desk">
<div class="connect-desk__profile-mini">
    <div class="profile-mini__wrapper">
        <h3>Заказчик</h3>
        <div class="profile-mini__top">
            <?= Html::img(Url::to('@web/img/man-brune.jpg'),['width'=>62,'height'=>62, 'alt'=>'Аватар заказчика']) ?>
            <div class="profile-mini__name five-stars__rate">
                <p><?= $model->customer->name ?></p>
            </div>
        </div>
        <p class="info-customer"><span><?= Yii::$app->i18n->format('{n, plural, one{# задание} few{# задания} many{# заданий} other{# заданий}}', ['n' => $model->customer->tasksCount], 'ru_RU') ?></span><span class="last-"><?= Yii::$app->formatter->format(date_create($model->customer->dtAdd)->diff(date_create('today')), 'duration') ?> на сайте</span></p>
        <?php //Html::a('Смотреть профиль', ['users/view', 'id' => $model->customer->id], ['class' => 'link-regular']) ?>
    </div>
</div>
<div id="chat-container">
<!--                    добавьте сюда атрибут task с указанием в нем id текущего задания-->
<chat class="connect-desk__chat" task="<?= $model->id ?>"></chat>
</div>
</section>