<?php

use yii\db\Migration;

/**
 * Class m231101_142314_create_table_author_book
 */
class m231101_142314_create_table_author_book extends Migration
{
    public function up()
    {
        $this->execute('
            CREATE TABLE `book_author` (
                `id` int NOT NULL AUTO_INCREMENT,
                `book_id` int NOT NULL,
                `author_id` int NOT NULL,
                `created_date` datetime NOT NULL,
                PRIMARY KEY (`id`),
                KEY `book_author_FK` (`book_id`),
                KEY `book_author_FK_1` (`author_id`),
                CONSTRAINT `book_author_FK` FOREIGN KEY (`book_id`) REFERENCES `book` (`id`),
                CONSTRAINT `book_author_FK_1` FOREIGN KEY (`author_id`) REFERENCES `author` (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
        ');
    }

    public function down()
    {
        $this->dropTable('book_author');
    }
}
