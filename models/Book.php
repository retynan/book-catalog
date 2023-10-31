<?php

namespace app\models;

use app\models\base\BBook;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

/**
 *
 * @property UploadedFile $_image
 */

class Book extends BBook
{
    public $_image;

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['_image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'author_ids' => 'Authors'
        ]);
    }

    public static function getAuthorsNameByBookId(int $id)
    {
        $data = '';

        $book = self::findOne($id);

        if (empty($book))
            return $data;

        if (empty($book->author_ids))
            return $data;

        foreach ($book->author_ids as $author)
            $data .= Author::getAuthorName($author) . ', ';

        return mb_substr($data, 0, -2);
    }
}
