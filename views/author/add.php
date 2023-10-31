<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\Author $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;

$this->title = 'Author Add';
$this->params['breadcrumbs'][] = ['label' => 'Authors List', 'url' => ['author/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-add">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin(['id' => 'author-add-form']); ?>

            <?= $form->field($model, 'first_name')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'middle_name') ?>

            <?= $form->field($model, 'last_name') ?>

            <div class="form-group">
                <?= Html::submitButton('Add', ['class' => 'btn btn-primary', 'name' => 'author-add-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>

</div>