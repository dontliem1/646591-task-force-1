<?php

/* @var $this yii\web\View */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Исполнители | TaskForce';
?>
<section class="user__search">
    <?php if (isset($sortings) && !empty($sortings)) : ?>
        <div class="user__search-link">
            <p>Сортировать по:</p>
            <ul class="user__search-list">
                <?php foreach ($sortings as $sorting => $label) {
                    $isActive = Yii::$app->request->get('sort') === $sorting;
                ?>
                    <li class="user__search-item<?php if ($isActive) {
                                                    echo ' user__search-item--current';
                                                } ?>">
                        <a href="<?= $isActive ? Url::to(['/users']) : Url::to(['/users', 'sort' => $sorting]) ?>" class="link-regular"><?= $label ?></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    <?php endif; ?>
    <?php foreach ($users as $user) : ?>
        <div class="content-view__feedback-card user__search-wrapper">
            <div class="feedback-card__top">
                <div class="user__search-icon">
                    <a href="#"><img src="<?= Url::to('@web/img/man-glasses.jpg') ?>" width="65" height="65" alt=""></a>
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
    <?php endforeach; ?>
</section>
<section class="search-task">
    <div class="search-task__wrapper">
        <?php
        $form = ActiveForm::begin([
            'method' => 'get',
            'options' => ['class' => 'search-task__form'],
            'action' => Url::to(['/users']),
        ]);
        echo $form->field($model, 'sort', ['options' => ['tag' => null]])->hiddenInput(['value' => Yii::$app->request->get('sort') ?? null])->label(false);
        echo $form->field($model, 'categories', ['options' => ['tag' => 'fieldset', 'class' => 'search-task__categories'], 'parts' => ['{label}' => '<legend>' . $model->getAttributeLabel('categories') . '</legend>']])->checkboxList($categories, ['unselect' => null, 'tag' => false, 'item' => function ($index, $label, $name, $checked, $value) {
            $checked = $checked ? ' checked' : '';
            return "<input class='visually-hidden checkbox__input' type='checkbox'{$checked} name='{$name}' value='{$value}' id='{$value}'><label for='{$value}'>{$label}</label>";
        }]);
        echo Html::beginTag('fieldset', ['class' => 'search-task__categories']);
        echo Html::tag('legend', 'Дополнительно');
        echo $form->field($model, 'isFree', ['options' => ['tag' => false], 'template' => '{input}{label}'])->checkbox(['class' => 'visually-hidden checkbox__input', 'uncheck' => null], false);
        echo $form->field($model, 'isOnline', ['options' => ['tag' => false], 'template' => '{input}{label}'])->checkbox(['class' => 'visually-hidden checkbox__input', 'uncheck' => null], false);
        echo $form->field($model, 'hasOpinions', ['options' => ['tag' => false], 'template' => '{input}{label}'])->checkbox(['class' => 'visually-hidden checkbox__input', 'uncheck' => null], false);
        echo $form->field($model, 'isBookmarked', ['options' => ['tag' => false], 'template' => '{input}{label}'])->checkbox(['class' => 'visually-hidden checkbox__input', 'uncheck' => null], false);
        echo Html::endTag('fieldset');
        echo $form->field($model, 'name', [
            'options' => ['tag' => false],
            'labelOptions' => ['class' => 'search-task__name'],
            'inputOptions' => ['type' => 'search', 'class' => 'input-middle input'],
        ]);
        echo Html::submitButton('Искать', ['class' => 'button']);
        ActiveForm::end();
        ?>
    </div>
</section>