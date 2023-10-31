<?php

use yii\db\Migration;

/**
 * Class m231030_211451_create_table_subscribe
 */
class m231030_211451_create_table_subscribe extends Migration
{
    public function up()
    {
        $this->execute('
            CREATE TABLE `subscribe` (
                `id` int NOT NULL AUTO_INCREMENT,
                `phone` varchar(15) NOT NULL,
                `author_id` int NOT NULL,
                `created_date` datetime NOT NULL,
                PRIMARY KEY (`id`),
                KEY `subscribe_FK` (`author_id`),
                CONSTRAINT `subscribe_FK` FOREIGN KEY (`author_id`) REFERENCES `author` (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
        ');
    }

    public function down()
    {
        $this->dropTable('subscribe');
    }
}
