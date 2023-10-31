<?php

namespace app\controllers;

use app\models\Book;
use app\models\Subscribe;
use Yii;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use yii\web\NotFoundHttpException;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSubscribe(int $id)
    {
        /**
         * @var Book $model
         */
        $book = Book::find()->where(['id' => $id, 'is_deleted' => 0])->limit(1)->one();

        if (empty($book))
            throw new NotFoundHttpException('Book not found.');

        $phone = Yii::$app->request->post('phone');

        if (empty($phone))
            throw new BadRequestHttpException('Field not filled in: phone number.');

        foreach ($book->author_ids as $author)
        {
            /**
             * @var Subscribe $subscribe
             */
            $subscribe = Subscribe::find()
                ->where(['phone' => $phone, 'author_id' => $author])
                ->limit(1)
                ->one();

            if ($subscribe)
                continue;

            $subscribe = new Subscribe();

            $subscribe->phone = $phone;
            $subscribe->author_id = $author;
            $subscribe->created_date = date('Y-m-d H:i:s');

            $subscribe->save();
        }
    }
}