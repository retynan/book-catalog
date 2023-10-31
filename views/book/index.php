<?php

/** @var yii\web\View $this */
/** @var \yii\data\ActiveDataProvider $provider */

use yii\bootstrap5\Button;
use yii\bootstrap5\Html;
use app\models\Book;

?>
<div class="book-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4"><?= Yii::$app->name ?></h1>
    </div>

    <div class="body-content">

        <?php
            if (!Yii::$app->user->isGuest)
                echo Html::a('Book Add', ['book/add']);
        ?>

        <div class="row">

            <?php
                try
                {
                    echo \yii\grid\GridView::widget([
                        'dataProvider' => $provider,
                        'columns' => [
                            'id',
                            'name',
                            [
                                'label' => 'Author(s)',
                                'attribute' => 'author_ids',
                                'content' => function ($model, $key, $index, $column)
                                {
                                    return Book::getAuthorsNameByBookId($model['id']);
                                }
                            ],
                            'description',
                            'year',
                            'isbn_10',
                            'isbn_13',
                            [
                                'attribute' => 'image',
                                'content' => function ($model, $key, $index, $column)
                                {
                                    if ($model['image'])
                                        return Html::a('View', ['/uploads/images/' . $model['image']], [
                                            'target' => '_blank'
                                        ]);
                                    else
                                        return null;
                                }
                            ],
                            Yii::$app->user->isGuest ? [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => 'Actions',
                                'template' => '{subscribe}',
                                'buttons' => [
                                    'subscribe' => function($url, $model, $key)
                                    {
                                        return Button::widget([
                                            'label' => 'Subscribe to the author(s)',
                                            'options' => [
                                                'class' => 'btn btn-link',
                                                'data-bs-toggle' => 'modal',
                                                'data-bs-target' => '#subscribe-modal',
                                                'data-book-id' => $model['id'],
                                                'data-book-authors-name' => Book::getAuthorsNameByBookId($model['id'])
                                            ]
                                        ]);
                                    }
                                ],
                            ] : [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => 'Actions',
                                'template' => '{update}{delete}',
                                'buttons' => [
                                    'update' => function($url, $model, $key)
                                    {
                                        return Html::a('Update', ['book/update', 'id' => $model['id']]);
                                    },
                                    'delete' => function($url, $model, $key)
                                    {
                                        return Html::a('Delete', ['book/delete', 'id' => $model['id']], [
                                            'data' => [
                                                'confirm' => 'Are you sure you want to delete this item?'
                                            ]
                                        ]);
                                    }
                                ],
                            ]
                        ]
                    ]);
                }
                catch (Throwable $e)
                {
                    echo $e->getMessage();
                }
            ?>

        </div>

    </div>
</div>

<div class="modal fade" id="subscribe-modal" tabindex="-1" aria-labelledby="subscribe-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="subscribe-modal-label">Subscribe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="mb-3">
                    <?= Html::label('Your Phone', null, [
                        'class' => 'col-lg-7 col-form-label'
                    ]) ?>

                    <?= Html::textInput('subscribe_phone', null, [
                        'id' => 'phone',
                        'class' => 'col-lg-3 form-control',
                        'maxlength' => 15
                    ]) ?>
                </div>

                <div class="alert alert-primary" role="alert" style="display: none;"></div>
                <div class="alert alert-danger" role="alert" style="display: none;"></div>
                <div class="alert alert-success" role="alert" style="display: none;"></div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Subscribe</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function ()
    {
        $('.btn-link').on('click', (event) =>
        {
            let authorsName = $(event.target).data('book-authors-name');
            let bookId = $(event.target).data('book-id');

            $('.alert-primary').html(
                `Be informed when there are new books from the author(s): ${authorsName}. We'll SMS message you when they're available.
            `).show();

            $('.btn-primary').on('click', () =>
            {
                $('.alert-danger').hide().html(null);
                $('.alert-success').hide().html(null);

                let phone = $('#phone').val();

                $.ajax({
                    method: 'POST',
                    url: `/subscribe/${bookId}`,
                    data: {phone: phone}
                }).done((response) =>
                {
                    $('#phone').val(null);
                    $('.alert-success').html(`You have successfully subscribed to the author(s): ${authorsName}`).show();
                }).fail((response) =>
                {
                    $('.alert-danger').html(response.responseText).show();
                });
            });
        });

        $("#subscribe-modal").on("hidden.bs.modal", function () {
            $('#phone').val(null);
            $('.alert-danger').hide().html(null);
            $('.alert-success').hide().html(null);

            $('.btn-primary').off('click');
        });

    });
</script>