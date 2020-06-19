<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\User */
/* @var $form yii\widgets\ActiveForm */

$form = ActiveForm::begin([
    'options' => [
        'class' => 'registration__user-form form-create'
    ],
    'fieldConfig' => [
        'options' => ['tag' => false],
        'errorOptions' => ['tag' => 'span'],
        'hintOptions' => ['tag' => 'span']
    ],
]);
echo $form->field($model, 'email', ['labelOptions' => ['class' => isset($model->errors['email'])?'input-danger':false]])->input('email', ['class' => 'input textarea', 'placeholder'=>'kumarm@mail.ru']);
echo $form->field($model, 'name', ['labelOptions' => ['class' => isset($model->errors['name'])?'input-danger':false]])->textInput(['class' => 'input textarea', 'placeholder'=>'Мамедов Кумар']);
echo $form->field($model, 'city', ['labelOptions' => ['class' => isset($model->errors['city'])?'input-danger':false]])->dropDownList($allCities, ['class' => 'multiple-select input town-select registration-town']);
echo $form->field($model, 'password', ['labelOptions' => ['class' => isset($model->errors['password'])?'input-danger':false]])->passwordInput(['class' => 'input textarea', 'minlength' => '8']);
echo Html::submitButton('Создать аккаунт', ['class' => 'button button__registration']);
ActiveForm::end();
?>