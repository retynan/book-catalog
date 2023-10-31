<?php

namespace app\models\base;

use app\models\Author;

/**
 * This is the model class for table "book".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $year
 * @property string|null $author_ids
 * @property string $isbn_10
 * @property string $isbn_13
 * @property string|null $image
 * @property int $is_deleted
 * @property string $created_date
 * @property string|null $updated_date
 */
class BBook extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'book';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'isbn_10', 'isbn_13', 'created_date'], 'required'],
            [['description'], 'string'],
            [['year', 'author_ids', 'created_date', 'updated_date'], 'safe'],
            [['is_deleted'], 'integer'],
            [['name', 'image'], 'string', 'max' => 256],
            [['isbn_10'], 'string', 'max' => 13],
            [['isbn_13'], 'string', 'max' => 17],
            [['isbn_10'], 'unique'],
            [['isbn_13'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'year' => 'Year',
            'author_ids' => 'Author Ids',
            'isbn_10' => 'Isbn 10',
            'isbn_13' => 'Isbn 13',
            'image' => 'Image',
            'is_deleted' => 'Is Deleted',
            'created_date' => 'Created Date',
            'updated_date' => 'Updated Date',
        ];
    }
}
