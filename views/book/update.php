<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\Author $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use app\models\Author;

$this->title = 'Update Book: ' . $model['name'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-add">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin(['id' => 'author-update-form']); ?>

            <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'description')->textarea() ?>

            <?= $form->field($model, 'year')->textInput(['maxlength' => 4]) ?>

            <?= $form->field($model, 'author_ids')->checkboxList(Author::getAllAuthorsForCheckbox()) ?>

            <?= $form->field($model, 'isbn_10')->textInput(['maxlength' => 13]) ?>

            <?= $form->field($model, 'isbn_13')->textInput(['maxlength' => 17]) ?>

            <?php if ($model['image']): ?>
                <?= $form->field($model, '_image')->fileInput()->label('Replace Image') ?>

                <div class="alert alert-info" role="alert">
                    The image file has been uploaded. Click on this <a target="_blank" href="/uploads/images/<?= $model['image'] ?>">link</a> to view.
                </div>
            <?php else: ?>

                <?= $form->field($model, '_image')->fileInput() ?>

            <?php endif ?>

            <div class="form-group">
                <?= Html::submitButton('Update', ['class' => 'btn btn-primary', 'name' => 'book-update-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>

</div>