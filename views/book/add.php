<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\Book $model */

use app\helpers\AuthorHelper;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Book Add';
$this->params['breadcrumbs'][] = $this->title;

$authors = AuthorHelper::getAllAuthorsForCheckbox();
?>
<div class="book-add">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin(['id' => 'author-add-form']); ?>

            <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'description')->textarea() ?>

            <?= $form->field($model, 'year')->textInput(['maxlength' => 4]) ?>

            <?php if ($authors): ?>

                <?= $form->field($model, '_authors')->checkboxList($authors) ?>

            <?php else: ?>
                <?= Html::label('Authors', null, [
                    'class' => 'col-lg-7 col-form-label'
                ]) ?>

                <div class="alert alert-info" role="alert">
                    No authors have been added yet. You can add one <a href="/author/add" title="Author Add">here</a>.
                </div>

            <?php endif; ?>

            <?= $form->field($model, 'isbn_10')->textInput(['maxlength' => 13]) ?>

            <?= $form->field($model, 'isbn_13')->textInput(['maxlength' => 17]) ?>

            <?= $form->field($model, '_image')->fileInput() ?>

            <div class="form-group">
                <?= Html::submitButton('Add', ['class' => 'btn btn-primary', 'name' => 'book-add-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>

</div>