<?php

use yii\db\Migration;
use \yii\db\Schema;

class m150910_195330_queue extends Migration
{
    public function up()
    {
        $this->createTable('queue_job',[
            'id' => Schema::TYPE_BIGPK,
            'queue' => Schema::TYPE_STRING,
            'attemps' => Schema::TYPE_SMALLINT,
            'data' => Schema::TYPE_TEXT,
            'status' => Schema::TYPE_SMALLINT,
            'created_at' => Schema::TYPE_INTEGER,
            'execution_time' => Schema::TYPE_INTEGER,
        ]);

        $this->createTable('queue_job_failed',[
            'id' => Schema::TYPE_BIGPK,
            'queue' => Schema::TYPE_STRING,
            'data' => Schema::TYPE_TEXT,
            'error' => Schema::TYPE_TEXT,
            'created_at' => Schema::TYPE_INTEGER,
        ]);

        $this->createIndex('idx_queue_job_1', 'queue_jobs', 'id', 0);
        $this->createIndex('idx_queue_job_failed_1', 'queue_jobs_failed', 'id', 0);
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
