<?php

/** @var yii\web\View $this */
/** @var \yii\data\ActiveDataProvider $provider */

use yii\bootstrap5\Html;
use yii\grid\GridView;

$this->title = 'Authors List';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="author-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="body-content">

        <?= Html::a('Author Add', ['author/add']) ?>

        <div class="row">

            <?php
                try
                {
                    echo GridView::widget([
                        'dataProvider' => $provider,
                        'columns' => [
                            'id',
                            'first_name',
                            'middle_name',
                            'last_name',
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => 'Actions',
                                'template' => '{update}{delete}',
                                'buttons' => [
                                    'update' => function($url, $model, $key)
                                    {
                                        return Html::a('Update', ['author/update', 'id' => $model->id]);
                                    },
                                    'delete' => function($url, $model, $key)
                                    {
                                        return Html::a('Delete', ['author/delete', 'id' => $model->id], [
                                            'data' => [
                                                'confirm' => 'Are you sure you want to delete this item?'
                                            ]
                                        ]);
                                    }
                                ],
                            ],
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
