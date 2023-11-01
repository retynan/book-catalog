<?php

/** @var yii\web\View $this */
/** @var \yii\data\ActiveDataProvider $provider */
/** @var string $year */

use yii\bootstrap5\Html;
use yii\grid\GridView;

$this->title = 'TOP Authors';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="author-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="body-content">

        <div class="row">

            <div class="input-group mb-3">

                <?= Html::input('text', 'year', $year, [
                    'id' => 'year',
                    'class' => 'form-control',
                    'placeholder' => 'Year',
                    'aria-label' => 'Year',
                    'aria-describedby' => 'button-top-search'
                ]) ?>

                <?= Html::Button('Search', [
                    'id' => 'button-top-search',
                    'class' => 'btn btn-primary'
                ]) ?>

            </div>

            <?php
                try
                {
                    echo GridView::widget([
                        'dataProvider' => $provider,
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'header' => 'Rank'
                            ],
                            'author_full_name',
                            'total'
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

<script>
    $(function ()
    {
        $('#year').inputmask("9999");

        $('#button-top-search').on('click', (event) =>
        {
            let year = $('#year').val();

            if (year)
                window.location = `/author/top/${year}`;
        });

        $('#year').keypress((event) =>
        {
            let key = event.which;

            if (key === 13)
                $('#button-top-search').click();
        });
    });
</script>
