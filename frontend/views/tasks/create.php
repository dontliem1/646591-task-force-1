<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Task */

$this->registerJsFile('/js/dropzone.js');
$this->title = 'Публикация нового задания';
?>
<section class="create__task">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="create__task-main">
        <?= $this->render('_form', [
            'model' => $model,
            'allCategories' => $allCategories,
        ]) ?>
        <div class="create__warnings">
            <div class="warning-item warning-item--advice">
                <h2>Правила хорошего описания</h2>
                <h3>Подробности</h3>
                <p>Друзья, не используйте случайный<br>
                    контент – ни наш, ни чей-либо еще. Заполняйте свои
                    макеты, вайрфреймы, мокапы и прототипы реальным
                    содержимым.</p>
                <h3>Файлы</h3>
                <p>Если загружаете фотографии объекта, то убедитесь,
                    что всё в фокусе, а фото показывает объект со всех
                    ракурсов.</p>
            </div>
            <?php if ($model->hasErrors()) : ?>
            <div class="warning-item warning-item--error">
                <h2>Ошибки заполнения формы</h2>
                <?php foreach ($model->getErrors() as $name => $errors) {
                    echo '<h3>'.$model->getAttributeLabel($name).'</h3><p>';
                    $countErrors = count($errors);
                    foreach ($errors as $count => $error) {
                        echo $error;
                        if ($count !== $countErrors) {
                            echo '<br>';
                        }
                    }
                    echo '</p>';
                } ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?= Html::submitButton('Опубликовать', ['class' => 'button', 'form' => 'task-form']) ?>
</section>

<?php $this->beginBlock('js'); ?>
<script>
  var dropzone = new Dropzone("div.create__file", {url: "/", paramName: "Attach"});
</script>
<?php $this->endBlock(); ?>