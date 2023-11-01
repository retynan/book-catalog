<?php

namespace app\models;

use app\models\base\BAuthor;

/**
 *
 * @property string $authorFullName
 */

class Author extends BAuthor
{
    public function getAuthorFullName()
    {
        return $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name;
    }
}
