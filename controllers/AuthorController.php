<?php

namespace app\controllers;

use app\models\Author;

use app\models\Book;
use app\models\BookAuthor;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class AuthorController extends Controller
{
    public array $errors = [];

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'add', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $query = Author::find();

        $provider = new ActiveDataProvider([
            'query' => $query
        ]);

        $query->where(['is_deleted' => 0]);

        $provider->pagination->pageSize = 40;
        $provider->setSort(['defaultOrder' => ['id' => SORT_DESC]]);

        return $this->render('index', ['provider' => $provider]);
    }

    public function actionTop(string $year = null)
    {
        $year = intval($year);

        if (empty($year))
            $year = date('Y');

        $query = (new Query())
            ->select([
                new Expression('COUNT(`author`.`id`) AS `total`'),
                new Expression('CONCAT (
                    `author`.`first_name`, SPACE(1), 
                    `author`.`middle_name`, SPACE(1), 
                    `author`.`last_name`
                ) AS `author_full_name`')
            ])
            ->from(Book::tableName())
            ->leftJoin(BookAuthor::tableName(), '`book_author`.`book_id` = `book`.`id`')
            ->leftJoin(Author::tableName(), '`author`.`id` = `book_author`.`author_id`')
            ->where(['`book`.`year`' => $year])
            ->andWhere(['`book`.`is_deleted`' => 0])
            ->groupBy('`author`.`id`')
            ->orderBy(['total' => SORT_DESC])
            ->limit(10);

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ]
        ]);

        return $this->render('top', ['provider' => $provider, 'year' => $year]);
    }

    public function actionAdd()
    {
        $model = new Author();

        if ($model->load(Yii::$app->request->post()))
        {
            $model->created_date = date('Y-m-d H:i:s');

            if (!$model->save())
                return $this->render('add', [
                    'model' => $model,
                ]);

            return $this->redirect(['author/index']);
        }

        return $this->render('add', [
            'model' => $model,
        ]);
    }

    public function actionUpdate(int $id)
    {
        /** @var Author $model */
        $model = Author::find()->where(['id' => $id, 'is_deleted' => 0])->limit(1)->one();

        if (empty($model))
            throw new NotFoundHttpException('Author not found.');

        if ($model->load(Yii::$app->request->post()))
        {
            $model->updated_date = date('Y-m-d H:i:s');

            if (!$model->save())
                return $this->render('update', [
                    'model' => $model
                ]);

            return $this->redirect(['author/index']);
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }

    public function actionDelete(int $id)
    {
        /** @var Author $model */
        $model = Author::find()->where(['id' => $id, 'is_deleted' => 0])->limit(1)->one();

        if (empty($model))
            throw new NotFoundHttpException('Author not found.');

        $model->is_deleted = 1;
        $model->updated_date = date('Y-m-d H:i:s');

        if (!$model->save())
            Yii::error($model->errors);

        return $this->redirect(['author/index']);
    }
}
