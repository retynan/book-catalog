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
                `id` INT auto_increment NOT NULL,
                `first_name` varchar(256) NOT NULL,
                `middle_name` varchar(256) NOT NULL,
                `last_name` varchar(256) NOT NULL,
                `is_deleted` TINYINT DEFAULT 0 NOT NULL,
                `created_date` DATETIME NOT NULL,
                `updated_date` DATETIME NULL,
                CONSTRAINT authors_PK PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
        ');
    }

    public function down()
    {
        $this->dropTable('author');
    }
}
