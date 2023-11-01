<?php

namespace app\helpers;

use app\models\Author;

class AuthorHelper
{
    public static function getAllAuthorsForCheckbox()
    {
        $result = [];

        $authors = Author::find()
            ->select(['id', 'first_name', 'middle_name', 'last_name'])
            ->where(['is_deleted' => 0])
            ->orderBy([
                'first_name' => SORT_ASC,
                'middle_name' => SORT_ASC,
                'last_name' => SORT_ASC
            ])->all();

        /** @var Author[] $authors */
        foreach ($authors as $author)
            $result[$author->id] = $author->authorFullName;

        return $result;
    }
}