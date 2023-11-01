<?php

namespace app\controllers;

use app\models\Book;
use app\models\BookAuthor;
use app\models\Subscribe;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class BookController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['add', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['add', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $query = Book::find();

        $provider = new ActiveDataProvider([
            'query' => $query
        ]);

        $provider->pagination->pageSize = 40;
        $provider->setSort(['defaultOrder' => ['id' => SORT_DESC]]);

        return $this->render('index', ['provider' => $provider]);
    }

    public function actionAdd()
    {
        $model = new Book();

        if ($model->load(Yii::$app->request->post()))
        {
            $model->created_date = date('Y-m-d H:i:s');

            $model->_image = UploadedFile::getInstance($model, '_image');

            if ($model->_image)
            {
                $fileName = Yii::$app->security->generateRandomString();
                $savePath = Yii::getAlias('@webroot/uploads/images/') . $fileName . '.' . $model->_image->extension;

                if (!file_exists(Yii::getAlias('@webroot/uploads/images')))
                    mkdir(Yii::getAlias('@webroot/uploads/images'), 0777, true);

                $model->_image->saveAs($savePath, false);

                $model->image = $fileName . '.' . $model->_image->extension;;
            }

            if (!$model->save())
            {
                return $this->render('add', [
                    'model' => $model
                ]);
            }

            if ($model->_authors)
                foreach ($model->_authors as $author)
                {
                    $bookAuthor = new BookAuthor();

                    $bookAuthor->book_id = $model->id;
                    $bookAuthor->author_id = $author;
                    $bookAuthor->created_date = date('Y-m-d H:i:s');

                    if (!$bookAuthor->save())
                        Yii::error($bookAuthor->errors);
                }

            $this->handleSubscribes($model);

            return $this->redirect(['book/index']);
        }

        return $this->render('add', [
            'model' => $model
        ]);
    }

    public function actionUpdate(int $id)
    {
        /** @var Book $model */
        $model = Book::find()->where(['id' => $id, 'is_deleted' => 0])->limit(1)->one();

        if (empty($model))
            throw new NotFoundHttpException('Book not found.');

        if ($model->load(Yii::$app->request->post(), 'Book'))
        {
            $model->updated_date = date('Y-m-d H:i:s');

            $model->_image = UploadedFile::getInstance($model, '_image');

            if ($model->_image)
            {
                $fileName = Yii::$app->security->generateRandomString();
                $savePath = Yii::getAlias('@webroot/uploads/images/') . $fileName . '.' . $model->_image->extension;

                if (!file_exists(Yii::getAlias('@webroot/uploads/images')))
                    mkdir(Yii::getAlias('@webroot/uploads/images'), 0777, true);

                $model->_image->saveAs($savePath, false);

                $model->image = $fileName . '.' . $model->_image->extension;;
            }

            if (!$model->save())
                return $this->render('update', [
                    'model' => $model
                ]);

            BookAuthor::deleteAll([
                'book_id' => $model->id
            ]);

            if ($model->_authors)
            {
                foreach ($model->_authors as $author)
                {
                    $bookAuthor = new BookAuthor();

                    $bookAuthor->book_id = $model->id;
                    $bookAuthor->author_id = $author;
                    $bookAuthor->created_date = date('Y-m-d H:i:s');

                    if (!$bookAuthor->save())
                        Yii::error($bookAuthor->errors);
                }
            }

            return $this->redirect(['book/index']);
        }

        $bookAuthors = $model->bookAuthors;

        if ($bookAuthors)
        {
            foreach ($bookAuthors as $bookAuthor)
                $model->_authors[] = $bookAuthor->author_id;
        }

        if (!file_exists(Yii::getAlias('@webroot/uploads/images/') . $model->image))
        {
            $model->image = null;

            if (!$model->save())
                Yii::error($model->errors);
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }

    public function actionDelete(int $id)
    {
        /** @var Book $model */
        $model = Book::find()->where(['id' => $id, 'is_deleted' => 0])->limit(1)->one();

        if (empty($model))
            throw new NotFoundHttpException('Book not found.');

        $model->is_deleted = 1;
        $model->updated_date = date('Y-m-d H:i:s');

        if (!$model->save())
            Yii::error($model->errors);

        return $this->redirect(['book/index']);
    }

    protected function handleSubscribes(Book $book)
    {
        $bookAuthors = $book->bookAuthors;

        if (empty($bookAuthors))
            return false;

        /** @var BookAuthor[] $bookAuthors */
        foreach ($bookAuthors as $bookAuthor)
        {
            /** @var Subscribe[] $subscribes */
            $subscribes = Subscribe::find()->where(['author_id' => $bookAuthor->author_id])->all();

            foreach ($subscribes as $subscribe)
            {
                $message = 'A new book ( '
                    . $book->name . ' ) by the author ('
                    . $bookAuthor->author->authorFullName . ') has been added to the catalog.';

                Yii::$app->sms->send($subscribe->phone, $message);
            }
        }

        return true;
    }
}
