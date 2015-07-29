<?php

use yii\db\Schema;
use yii\db\Migration;

class m150621_132934_addIpTable extends Migration
{
    public function up()
    {
        $this->createTable('ip_location', [
            'id' => Schema::TYPE_PK,
            'ip' => Schema::TYPE_STRING . ' NOT NULL',
            'latitude' => Schema::TYPE_DOUBLE,
            'longitude' => Schema::TYPE_DOUBLE,
            'city' => Schema::TYPE_STRING,
            'country' => Schema::TYPE_STRING,
            'street_name' => Schema::TYPE_STRING,
            'street_number' => Schema::TYPE_STRING,
            'data' => Schema::TYPE_TEXT,
        ]);

        $this->dropTable('log');
    }

    public function down()
    {
        echo "m150621_132934_addIpTable cannot be reverted.\n";

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
