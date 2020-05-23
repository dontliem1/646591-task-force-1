<?php

/* @var $this yii\web\View */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Задания | TaskForce';
?>
<section class="new-task">
    <div class="new-task__wrapper">
        <h1>Новые задания</h1>
        <?php foreach ($tasks as $task) : ?>
            <div class="new-task__card">
                <div class="new-task__title">
                    <a href="#" class="link-regular">
                        <h2><?= $task['name'] ?></h2>
                    </a>
                    <a class="new-task__type link-regular" href="<?= Url::to(['/tasks', 'categories' => [$task['icon']]]) ?>">
                        <p><?= $task['category'] ?></p>
                    </a>
                </div>
                <div class="new-task__icon new-task__icon--<?= $task['icon'] ?>"></div>
                <p class="new-task_description">
                    <?= $task['description'] ?>
                </p>
                <b class="new-task__price new-task__price--translation"><?= $task['budget'] ?><b> ₽</b></b>
                <p class="new-task__place"><?= $task['city'] ?></p>
                <span class="new-task__time"><?= Yii::$app->formatter->format($task['dt_add'], 'relativeTime') ?></span>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="new-task__pagination">
        <ul class="new-task__pagination-list">
            <li class="pagination__item"><a href="#"></a></li>
            <li class="pagination__item pagination__item--current">
                <a>1</a></li>
            <li class="pagination__item"><a href="#">2</a></li>
            <li class="pagination__item"><a href="#">3</a></li>
            <li class="pagination__item"><a href="#"></a></li>
        </ul>
    </div>
</section>
<section class="search-task">
    <div class="search-task__wrapper">

    <?php
        $form = ActiveForm::begin([
            'method' => 'get',
            'options' => ['class' => 'search-task__form'],
            'action' => Url::to(['/tasks']),
        ]);
        echo $form->field($model, 'categories', ['options' => ['tag' => 'fieldset', 'class' => 'search-task__categories'], 'parts' => ['{label}' => '<legend>' . $model->getAttributeLabel('categories') . '</legend>']])->checkboxList($allCategories, ['unselect' => null, 'tag' => false, 'item' => function ($index, $label, $name, $checked, $value) {
            $checked = $checked ? ' checked' : '';
            return "<input class='visually-hidden checkbox__input' type='checkbox'{$checked} name='{$name}' value='{$value}' id='{$value}'><label for='{$value}'>{$label}</label>";
        }]);
        echo Html::beginTag('fieldset', ['class' => 'search-task__categories']);
        echo Html::tag('legend', 'Дополнительно');
        echo $form->field($model, 'hasNoReplies', ['options' => ['tag' => false], 'template' => '{input}{label}'])->checkbox(['class' => 'visually-hidden checkbox__input', 'uncheck' => null], false);
        echo $form->field($model, 'isRemote', ['options' => ['tag' => false], 'template' => '{input}{label}'])->checkbox(['class' => 'visually-hidden checkbox__input', 'uncheck' => null], false);
        echo Html::endTag('fieldset');
        echo $form->field($model, 'period', [
            'options' => ['tag' => false],
            'labelOptions' => ['class' => 'search-task__name'],
            'inputOptions' => ['class' => 'multiple-select input']
        ])->dropDownList($periods);
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