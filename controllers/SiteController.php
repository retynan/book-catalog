<?php

namespace app\controllers;

use app\models\Author;
use app\models\Subscribe;
use Yii;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use yii\web\NotFoundHttpException;
use yii\web\Response;

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
        Yii::$app->response->format = Response::FORMAT_JSON;

        /**
         * @var Author $author
         */
        $author = Author::find()->where(['id' => $id, 'is_deleted' => 0])->limit(1)->one();

        if (empty($author))
            throw new NotFoundHttpException('Author not found.');

        $phone = Yii::$app->request->post('phone');

        if (empty($phone))
            throw new BadRequestHttpException('Please enter your phone number.');

        /**
         * @var Subscribe $subscribe
         */
        $subscribe = Subscribe::find()
            ->where(['phone' => $phone, 'author_id' => $author])
            ->limit(1)
            ->one();

        if ($subscribe)
            return [
                'message' => 'You are already subscribed to the author ' . $author->authorFullName . '.',
                'error' => false
            ];

        $subscribe = new Subscribe();

        $subscribe->phone = $phone;
        $subscribe->author_id = $author->id;
        $subscribe->created_date = date('Y-m-d H:i:s');

        if ($subscribe->save())
        {
            Yii::$app->response->statusCode = 201;

            return [
                'message' => 'You have successfully subscribed to the author ' . $author->authorFullName . '.',
                'error' => false
            ];
        }
        else
        {
            Yii::error($subscribe->errors);

            return [
                'message' => 'Something went wrong. Try again later.',
                'error' => true
            ];
        }
    }
}