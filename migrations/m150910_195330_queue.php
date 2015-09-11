<?php

use yii\db\Migration;
use \yii\db\Schema;

class m150910_195330_queue extends Migration
{
    public function up()
    {
        $this->createTable('job_queue',[
            'id' => Schema::TYPE_BIGPK,
            'queue' => Schema::TYPE_STRING,
            'attempts' => Schema::TYPE_SMALLINT,
            'data' => Schema::TYPE_TEXT,
            'status' => Schema::TYPE_SMALLINT,
            'created_at' => Schema::TYPE_INTEGER,
            'execution_time' => Schema::TYPE_INTEGER,
        ]);

        $this->createIndex('idx_queue_job_1', 'job_queue', 'id', 0);
    }

    public function down()
    {
        echo "m150910_195330_queue cannot be reverted.\n";

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
