<?php

use yii\db\Migration;

/**
 * Class m231028_223412_create_table_author
 */
class m231028_223412_create_table_author extends Migration
{
    public function up()
    {
        $this->execute('
            CREATE TABLE `author` (
                `id` int NOT NULL AUTO_INCREMENT,
                `first_name` varchar(256) NOT NULL,
                `middle_name` varchar(256) NOT NULL,
                `last_name` varchar(256) NOT NULL,
                `is_deleted` tinyint NOT NULL DEFAULT 0,
                `created_date` datetime NOT NULL,
                `updated_date` datetime DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `author_first_name_IDX` (`first_name`,`middle_name`,`last_name`) USING BTREE
            ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
        ');
    }

    public function down()
    {
        $this->dropTable('author');
    }
}
