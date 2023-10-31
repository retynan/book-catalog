<?php

namespace app\controllers;

use app\models\Author;
use app\models\Book;
use app\models\Subscribe;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class BookController extends Controller
{
    /**
     * {@inheritdoc}
     */
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
        $provider = new ActiveDataProvider([
            'query' =>  (new Query())->from(Book::tableName())->where(['is_deleted' => 0]),
            'pagination' => [
                'pageSize' => 40,
            ]
        ]);

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
                    mkdir(Yii::getAlias('@webroot/uploads/images'), 0777);

                $model->_image->saveAs($savePath, false);

                $model->image = $fileName . '.' . $model->_image->extension;;
            }

            if (!$model->save())
                throw new BadRequestHttpException($model->errors);

            $this->handleSubscribes($model);

            return $this->redirect(['book/index']);
        }

        return $this->render('add', [
            'model' => $model,
        ]);
    }

    public function actionUpdate(int $id)
    {
        /** @var Book $model */
        $model = Book::find()->where(['id' => $id, 'is_deleted' => 0])->limit(1)->one();

        if (empty($model))
            throw new NotFoundHttpException('Book not found.');

        if ($model->load(Yii::$app->request->post()))
        {
            $model->updated_date = date('Y-m-d H:i:s');

            $model->_image = UploadedFile::getInstance($model, '_image');

            if ($model->_image)
            {
                $fileName = Yii::$app->security->generateRandomString();
                $savePath = Yii::getAlias('@webroot/uploads/images/') . $fileName . '.' . $model->_image->extension;

                if (!file_exists(Yii::getAlias('@webroot/uploads/images')))
                    mkdir(Yii::getAlias('@webroot/uploads/images'), 0777);

                $model->_image->saveAs($savePath, false);

                $model->image = $fileName . '.' . $model->_image->extension;;
            }

            if (!$model->save())
                throw new BadRequestHttpException($model->errors);

            return $this->redirect(['book/index']);
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

        $model->save();

        return $this->redirect(['book/index']);
    }

    protected function handleSubscribes(Book $book)
    {
        if (empty($book->author_ids))
            return false;

        foreach ($book->author_ids as $author)
        {
            /** @var Subscribe[] $subscribes */
            $subscribes = Subscribe::find()->where(['author_id' => $author])->all();

            foreach ($subscribes as $subscribe)
            {
                $authorFullName = Author::getAuthorName($author);

                if (empty($authorFullName))
                    continue;

                $message = 'A new book by the author has been added to the catalog ' . $authorFullName . '.';

                Yii::$app->sms->send($subscribe->phone, $message);
            }
        }

        return true;
    }
}
