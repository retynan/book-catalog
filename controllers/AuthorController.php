<?php

namespace app\controllers;

use app\models\Author;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class AuthorController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
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
        $provider = new ActiveDataProvider([
            'query' => (new Query())->from(Author::tableName())->where(['is_deleted' => 0]),
            'pagination' => [
                'pageSize' => 40,
            ]
        ]);

        return $this->render('index', ['provider' => $provider]);
    }

    public function actionAdd()
    {
        $model = new Author();

        if ($model->load(Yii::$app->request->post()))
        {
            $model->created_date = date('Y-m-d H:i:s');

            $model->save();

            return $this->redirect(['author/index']);
        }

        return $this->render('add', [
            'model' => $model,
        ]);
    }

    public function actionUpdate(int $id)
    {
        $model = Author::find()->where(['id' => $id, 'is_deleted' => 0])->limit(1)->one();

        if (empty($model))
            throw new NotFoundHttpException('Author not found.');

        if ($model->load(Yii::$app->request->post()))
        {
            $model->updated_date = date('Y-m-d H:i:s');

            $model->save();

            return $this->redirect(['author/index']);
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }

    public function actionDelete(int $id)
    {
        $model = Author::find()->where(['id' => $id, 'is_deleted' => 0])->limit(1)->one();

        if (empty($model))
            throw new NotFoundHttpException('Author not found.');

        $model->is_deleted = 1;
        $model->updated_date = date('Y-m-d H:i:s');

        $model->save();

        return $this->redirect(['author/index']);
    }
}
