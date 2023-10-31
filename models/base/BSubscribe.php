<?php

namespace app\models\base;

use app\models\Author;

/**
 * This is the model class for table "subscribe".
 *
 * @property int $id
 * @property string $phone
 * @property int $author_id
 * @property string $created_date
 *
 * @property Author $author
 */
class BSubscribe extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subscribe';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone', 'author_id', 'created_date'], 'required'],
            [['author_id'], 'integer'],
            [['created_date'], 'safe'],
            [['phone'], 'string', 'max' => 15],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Author::class, 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => 'Phone',
            'author_id' => 'Author ID',
            'created_date' => 'Created Date',
        ];
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }
}
