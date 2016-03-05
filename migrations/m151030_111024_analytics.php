<?php

use yii\db\Migration;
use yii\db\Schema;

class m151030_111024_analytics extends Migration
{
    public function up()
    {
        $this->createTable('tracking_event',[
            'id' => Schema::TYPE_BIGPK,
            'session' => Schema::TYPE_STRING,
            'type' => Schema::TYPE_STRING,
            'created_at' => Schema::TYPE_INTEGER,
            'user_id' => Schema::TYPE_INTEGER,
            'data' => Schema::TYPE_STRING,
            'country' => Schema::TYPE_STRING,
            'city' => Schema::TYPE_STRING,
            'device' => Schema::TYPE_SMALLINT,
            'timestamp' => Schema::TYPE_BIGINT,
        ]);
    }

    public function down()
    {
        echo "m151030_111024_analytics cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
