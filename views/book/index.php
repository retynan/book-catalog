<?php

/** @var yii\web\View $this */
/** @var \yii\data\ActiveDataProvider $provider */
/** @var app\models\Book $model */

use yii\bootstrap5\Html;
use yii\grid\GridView;

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
                    echo GridView::widget([
                        'dataProvider' => $provider,
                        'columns' => [
                            'id',
                            [
                                'attribute' => 'name',
                                'content' => function ($model, $key, $index, $column)
                                {
                                    if (date('Y-m-d', strtotime($model->created_date)) == date ('Y-m-d'))
                                        return $model->name . '<span class="badge bg-success">New</span>';
                                    else
                                        return $model->name;
                                }
                            ],
                            [
                                'label' => 'Author(s)',
                                'content' => function ($model, $key, $index, $column)
                                {
                                    $bookAuthors = $model->bookAuthors;

                                    if (empty($bookAuthors))
                                        return null;

                                    $result = null;

                                    /** @var \app\models\BookAuthor[] $bookAuthors */
                                    foreach ($bookAuthors as $bookAuthor)
                                    {
                                        $author = $bookAuthor->author;

                                        if ($author)
                                            $result .= Html::tag('span', $author->authorFullName, [
                                                'class' => 'badge rounded-pill bg-primary',
                                                'data-bs-toggle' => 'modal',
                                                'data-bs-target' => '#subscribe-modal',
                                                'data-author-id' => $author->id,
                                                'data-author-name' => $author->authorFullName
                                        ]);
                                    }

                                    return $result;
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
                                        return Html::a('View', ['/uploads/images/' . $model->image], [
                                            'target' => '_blank'
                                        ]);
                                    else
                                        return null;
                                }
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => 'Actions',
                                'template' => '{update}{delete}',
                                'visible' => !Yii::$app->user->isGuest,
                                'buttons' => [
                                    'update' => function($url, $model, $key)
                                    {
                                        return Html::a('Update', ['book/update', 'id' => $model->id]);
                                    },
                                    'delete' => function($url, $model, $key)
                                    {
                                        return Html::a('Delete', ['book/delete', 'id' => $model->id], [
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
                        'maxlength' => 15,
                    ]) ?>
                </div>

                <div class="alert alert-primary" role="alert" style="display: none;"></div>
                <div class="alert alert-warning" role="alert" style="display: none;"></div>
                <div class="alert alert-success" role="alert" style="display: none;"></div>
                <div class="alert alert-danger" role="alert" style="display: none;"></div>

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
        $('#phone').inputmask("(999) 999-9999");

        $('.badge').on('click', (event) =>
        {
            let authorId = $(event.target).data('author-id');
            let authorName = $(event.target).data('author-name');

            $('.alert-primary').html(
                `Be informed when there are new books from the author: ${authorName}. We'll SMS message you when they're available.
            `).show();

            $('.btn-primary').on('click', () =>
            {
                $('.alert-warning').hide().html(null);
                $('.alert-success').hide().html(null);
                $('.alert-danger').hide().html(null);

                let phone = $('#phone').val();

                $.ajax({
                    method: 'POST',
                    url: `/subscribe/${authorId}`,
                    data: {phone: phone}
                }).done((response, status, jqXHR) =>
                {
                    $('#phone').val(null);

                    if (jqXHR.status == 201)
                        $('.alert-success').html(response.message).show();
                    else
                    {
                        if (response.error)
                            $('.alert-danger').html(response.message).show();
                        else
                            $('.alert-warning').html(response.message).show();
                    }
                }).fail((response, status, jqXHR) =>
                {
                    $('#phone').val(null);
                    $('.alert-danger').html(response.responseJSON.message).show();
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