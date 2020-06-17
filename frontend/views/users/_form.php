<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\User */
/* @var $form yii\widgets\ActiveForm */

$form = ActiveForm::begin([
    // 'action' => 'users/create',
    // 'enableClientValidation' => true,
    'options' => ['class' => 'registration__user-form form-create'],
    'fieldConfig' => ['options' => ['tag' => false], 'labelOptions' => ['class' => false]],
]);
echo $form->field($model, 'email')->input('email', ['class' => 'input textarea', 'placeholder'=>'kumarm@mail.ru']);
echo '<span>Введите валидный адрес электронной почты</span>';
echo $form->field($model, 'name')->textInput(['class' => 'input textarea', 'placeholder'=>'Мамедов Кумар']);
echo '<span>Введите ваше имя и фамилию</span>';
echo $form->field($model, 'city_id')->dropDownList($allCities, ['class' => 'multiple-select input town-select registration-town']);
echo '<span>Укажите город, чтобы находить подходящие задачи</span>';
echo $form->field($model, 'password', [
    'labelOptions' => ['class' => 'input-danger']
])->passwordInput(['class' => 'input textarea']);
echo '<span>Длина пароля от 8 символов</span>';
echo Html::submitButton('Создать аккаунт', ['class' => 'button button__registration']);
ActiveForm::end();
?>