<?php

namespace app\models;

use app\models\base\BBook;
use yii\helpers\ArrayHelper;

/**
 *
 * @property UploadedFile $_image
 * @property array $_authors
 */

class Book extends BBook
{
    public $_image;
    public $_authors;

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['_authors'], 'safe'],
            [['_image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            '_authors' => 'Authors'
        ]);
    }
}
