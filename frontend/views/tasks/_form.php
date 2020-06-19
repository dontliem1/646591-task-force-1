<?php

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Task */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin([
    'id' => 'task-form',
    'options' => [
        'class' => 'create__task-form form-create',
        'enctype' => 'multipart/form-data'
    ],
    'fieldConfig' => [
        'hintOptions' => [
            'tag' => 'span'
        ],
        'errorOptions' => [
            'tag' => 'span'
        ],
        'template' => "{label}\n{input}\n{error}\n{hint}",
    ]
]);
echo $form->field($model, 'name', ['options' => ['tag' => false], 'labelOptions' => ['class' => isset($model->errors['name'])?'input-danger':false]])->textInput(['maxlength' => true, 'class' => 'input textarea', 'placeholder' => 'Повесить полку']);
echo $form->field($model, 'description', ['options' => ['tag' => false], 'labelOptions' => ['class' => isset($model->errors['description'])?'input-danger':false]])->textarea(['rows' => 7, 'class' => 'input textarea']);
echo $form->field($model, 'category_id', ['options' => ['tag' => false], 'labelOptions' => ['class' => isset($model->errors['category_id'])?'input-danger':false]])->dropDownList($allCategories, ['class' => 'multiple-select input multiple-select-big']);
echo $form->field($model, 'uploadedFiles[]', ['options' => ['tag' => false], 'labelOptions' => ['class' => isset($model->errors['uploadedFiles'])?'input-danger':false], 'template' => "{label}\n{hint}\n<div class='create__file'>\n<span>Добавить новый файл</span>\n</div>\n{input}\n{error}"])->fileInput(['multiple' => true, 'class' => 'dropzone']);
echo $form->field($model, 'address', ['options' => ['tag' => false]])->textInput(['maxlength' => true, 'class' => 'input-navigation input-middle input', 'placeholder' => 'Санкт-Петербург, Калининский район']);
echo '<div class="create__price-time">';
echo $form->field($model, 'budget', ['options' => ['class' => 'create__price-time--wrapper'], 'labelOptions' => ['class' => isset($model->errors['budget'])?'input-danger':false]])->input('number',['class' => 'input textarea input-money', 'placeholder' => '1000', 'min' => 1]);
echo $form->field($model, 'expire', ['options' => ['class' => 'create__price-time--wrapper'], 'labelOptions' => ['class' => isset($model->errors['expire'])?'input-danger':false]])->input('date',['class' => 'input-middle input input-date', 'placeholder' => '10.11.2020']);
echo '</div>';
ActiveForm::end();
