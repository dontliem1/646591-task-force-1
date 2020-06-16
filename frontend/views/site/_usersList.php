<?php
use Yii;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
?>
 
 <div class="content-view__feedback-card user__search-wrapper">
    <div class="feedback-card__top">
        <div class="user__search-icon">
            <a href="#"><?= Yii::$app->formatter->asImage('@web/img/man-glasses.jpg', ['width' => 65, 'height' => 65]) ?></a>
            <span><?= Yii::$app->i18n->format('{n, plural, one{# задание} few{# задания} many{# заданий} other{# заданий}}', ['n' => $user['tasks']], 'ru_RU'); ?></span>
            <span><?= Yii::$app->i18n->format('{n, plural, one{# отзыв} few{# отзыва} many{# отзывов} other{# отзывов}}', ['n' => $user['opinions']], 'ru_RU'); ?></span>
        </div>
        <div class="feedback-card__top--name user__search-card">
            <p class="link-name"><a href="#" class="link-regular"><?= $user['name'] ?></a></p>
            <?php
            $rating = round($user['rating'], 2);
            for ($i = 1; $i <= 5; $i++) {
                echo ($i <= $rating ? '<span>' : '<span class="star-disabled">') . '</span>';
            }
            ?>
            <b><?= $rating ? $rating : '' ?></b>
            <p class="user__search-content"><?= $user['about'] ?></p>
        </div>
        <span class="new-task__time">
            <?= 'Был на сайте ' . Yii::$app->formatter->format($user['last_activity_time'], 'relativeTime') ?>
        </span>
    </div>
    <div class="link-specialization user__search-link--bottom">
        <?php $chosenCategories = explode(', ', $user['categories']);
        foreach ($chosenCategories as $category) {
            echo '<a href="#" class="link-regular">' . $categories[$category] . '</a> ';
        }
        ?>
    </div>
</div>