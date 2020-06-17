<?php

/* @var $this yii\web\View */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Регистрация аккаунта';
?>
<section class="registration__user">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="registration-wrapper">
    <?= $this->render('_form', [
        'model' => $model,
        'allCities' => $allCities,
    ]) ?>
    </div>
</section>

<?php $this->beginBlock('woman'); ?>
<div class="clipart-woman"><?= Html::img(Url::to('@web/img/clipart-woman.png'),['width'=>238,'height'=>450]) ?></div>
<div class="clipart-message">
    <div class="clipart-message-text">
    <h2>Знаете ли вы, что?</h2>
    <p>После регистрации вам будет доступно более
        двух тысяч заданий из двадцати разных категорий.</p>
        <p>В среднем, наши исполнители зарабатывают
        от 500 рублей в час.</p>
    </div>
</div>
<?php $this->endBlock(); ?>
