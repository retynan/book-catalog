<?php

namespace app\models;

use app\models\base\BAuthor;

class Author extends BAuthor
{
    public static function getAllAuthorsForCheckbox()
    {
        $authors = self::find()
            ->select(['id', 'first_name', 'middle_name', 'last_name'])
            ->where(['is_deleted' => 0])
            ->orderBy([
                'first_name' => SORT_ASC,
                'middle_name' => SORT_ASC,
                'last_name' => SORT_ASC
            ])
            ->asArray()->all();

        foreach ($authors as $author)
            $data[$author['id']] = $author['first_name'] . ' ' . $author['middle_name'] . ' ' . $author['last_name'];

        return $data;
    }

    public static function getAuthorName($id)
    {
        $data = '';

        if ($id)
        {
            $author = self::findOne($id);

            if ($author)
                $data = $author->first_name . ' ' . $author->middle_name . ' ' . $author->last_name;
        }

        return $data;
    }
}
