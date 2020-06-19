<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

/* @var $this yii\web\View */

$this->registerJsFile('/js/main.js');
$this->title = 'TaskForce';
?>
<div class="landing-top">
    <h1>Работа для всех.<br>
    Найди исполнителя на любую задачу.</h1>
    <p>Сломался кран на кухне? Надо отправить документы? Нет времени самому гулять с собакой?
        У нас вы быстро найдёте исполнителя для любой жизненной ситуации?<br>
        Быстро, безопасно и с гарантией. Просто, как раз, два, три. </p>
    <a class="button" href="<?= Url::to(['/signup']) ?>">Создать аккаунт</a>
</div>
<div class="landing-center">
    <div class="landing-instruction">
        <div class="landing-instruction-step">
            <div class="instruction-circle circle-request"></div>
            <div class="instruction-description">
                <h3>Публикация заявки</h3>
                <p>Создайте новую заявку.</p>
                <p>Опишите в ней все детали
                    и  стоимость работы.</p>
            </div>
        </div>
        <div class="landing-instruction-step">
            <div class="instruction-circle  circle-choice"></div>
            <div class="instruction-description">
                <h3>Выбор исполнителя</h3>
                <p>Получайте отклики от мастеров.</p>
                <p>Выберите подходящего<br>
                    вам исполнителя.</p>
            </div>
        </div>
        <div class="landing-instruction-step">
            <div class="instruction-circle  circle-discussion"></div>
            <div class="instruction-description">
                <h3>Обсуждение деталей</h3>
                <p>Обсудите все детали работы<br>
                    в нашем внутреннем чате.</p>
            </div>
        </div>
        <div class="landing-instruction-step">
            <div class="instruction-circle circle-payment"></div>
            <div class="instruction-description">
                <h3>Оплата&nbsp;работы</h3>
                <p>По завершении работы оплатите
                    услугу и закройте задание</p>
            </div>
        </div>
    </div>
    <div class="landing-notice">
    <div class="landing-notice-card card-executor">
        <h3>Исполнителям</h3>
        <ul class="notice-card-list">
            <li>
                Большой выбор заданий
            </li>
            <li>
                Работайте где  удобно
            </li>
            <li>
                Свободный график
            </li>
            <li>
                Удалённая работа
            </li>
            <li>
                Гарантия оплаты
            </li>
        </ul>
    </div>
    <div class="landing-notice-card card-customer">
        <h3>Заказчикам</h3>
        <ul class="notice-card-list">
            <li>
                Исполнители на любую задачу
            </li>
            <li>
                Достоверные отзывы
            </li>
            <li>
                Оплата по факту работы
            </li>
            <li>
                Экономия времени и денег
            </li>
            <li>
                Выгодные цены
            </li>
        </ul>
    </div>
    </div>
</div>
<div class="landing-bottom">
<?php echo ListView::widget([
    'dataProvider' => $dataProvider,
    'layout' => "<h2>Последние задания на сайте</h2>{items}",
    'itemOptions' => ['class' => 'landing-task'],
    'itemView' => function ($model, $key, $index, $widget) {
        return '<div class="landing-task-top task-'.$model->category->icon.'"></div>
            <div class="landing-task-description">
                <h3>'.Html::a($model->name, ['tasks/view', 'id' => $model->id], ['class' => 'link-regular']).'</h3>
                <p>'.$model->description.'</p>
            </div>
            <div class="landing-task-info">
                <div class="task-info-left">
                    <p>'.Html::a($model->category->name, ['/tasks', 'categories' => [$model->category->icon]], ['class' => 'link-regular']).'</p>
                    <p>'.Yii::$app->formatter->format($model->dtAdd, 'relativeTime').'</p>
                </div>
                <span>'.$model->budget.' <b>₽</b></span>
            </div>';
    },
    'options' => ['class' => 'landing-bottom-container'],
]) ?>
    <div class="landing-bottom-container">
        <a href="<?= Url::to(['/tasks']) ?>" class="button red-button">смотреть все задания</a>
    </div>
</div>
<?php $this->beginBlock('modal'); ?>
<section class="modal enter-form form-modal" id="enter-form">
        <h2>Вход на сайт</h2>
            <?php
            $form = ActiveForm::begin([
                'enableAjaxValidation' => true,
                'fieldConfig' => [
                    'inputOptions' => ['class' => 'enter-form-email input input-middle'],
                    'options' => ['tag' => 'p'],
                    'errorOptions' => ['tag' => 'span'],
                ],
            ]);
            echo $form->field($model, 'email', ['labelOptions' => ['class' => isset($model->errors['email'])?'form-modal-description input-danger':'form-modal-description']])->input('email');
            echo $form->field($model, 'password', ['labelOptions' => ['class' => isset($model->errors['password'])?'form-modal-description input-danger':'form-modal-description']])->passwordInput(['minlength' => '8']);
            echo Html::submitButton('Войти', ['class' => 'button']);
            ActiveForm::end();
            ?>
        <button class="form-modal-close" type="button">Закрыть</button>
    </section>
<?php $this->endBlock(); ?>