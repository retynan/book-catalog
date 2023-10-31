<?php

use yii\db\Migration;

/**
 * Class m231028_233128_create_table_book
 */
class m231028_233128_create_table_book extends Migration
{
    public function up()
    {
        $this->execute('
            CREATE TABLE `book` (
                `id` int NOT NULL AUTO_INCREMENT,
                `name` varchar(256) NOT NULL,
                `description` text,
                `year` varchar(4) DEFAULT NULL,
                `author_ids` json DEFAULT NULL,
                `isbn_10` varchar(13) NOT NULL,
                `isbn_13` varchar(17) NOT NULL,
                `image` varchar(256) DEFAULT NULL,
                `is_deleted` TINYINT DEFAULT 0 NOT NULL,
                `created_date` datetime NOT NULL,
                `updated_date` datetime DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `book_isbn_10_UN` (`isbn_10`),
                UNIQUE KEY `book_isbn_13_UN` (`isbn_13`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
        ');
    }

    public function down()
    {
        $this->dropTable('book');
    }
}
