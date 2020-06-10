<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */

$form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    'options' => ['class' => 'search-task__form'],
]);
// echo $form->field($model, 'sort', ['options' => ['tag' => null]])->hiddenInput(['value' => Yii::$app->request->get('sort') ?? null])->label(false);
echo $form->field($model, 'categories', ['options' => ['tag' => 'fieldset', 'class' => 'search-task__categories'], 'parts' => ['{label}' => '<legend>' . $model->getAttributeLabel('categories') . '</legend>']])->checkboxList($allCategories, ['unselect' => null, 'tag' => false, 'item' => function ($index, $label, $name, $checked, $value) {
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