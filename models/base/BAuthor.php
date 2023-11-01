<?php

namespace app\models\base;

use app\models\BookAuthor;
use app\models\Subscribe;

/**
 * This is the model class for table "author".
 *
 * @property int $id
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property int $is_deleted
 * @property string $created_date
 * @property string|null $updated_date
 *
 * @property BookAuthor[] $bookAuthors
 * @property Subscribe[] $subscribes
 */
class BAuthor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'author';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'middle_name', 'last_name', 'created_date'], 'required'],
            [['is_deleted'], 'integer'],
            [['created_date', 'updated_date'], 'safe'],
            [['first_name', 'middle_name', 'last_name'], 'string', 'max' => 256],
            [['first_name', 'middle_name', 'last_name'], 'unique', 'targetAttribute' => ['first_name', 'middle_name', 'last_name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'middle_name' => 'Middle Name',
            'last_name' => 'Last Name',
            'is_deleted' => 'Is Deleted',
            'created_date' => 'Created Date',
            'updated_date' => 'Updated Date',
        ];
    }

    /**
     * Gets query for [[BookAuthors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookAuthors()
    {
        return $this->hasMany(BookAuthor::class, ['author_id' => 'id']);
    }

    /**
     * Gets query for [[Subscribes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubscribes()
    {
        return $this->hasMany(Subscribe::class, ['author_id' => 'id']);
    }
}
