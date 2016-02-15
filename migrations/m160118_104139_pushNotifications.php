<?php

use yii\db\Schema;
use yii\db\Migration;

class m160118_104139_pushNotifications extends Migration
{
    public function up()
    {
        $this->createTable('mobile_devices', [
            'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
            0 => 'PRIMARY KEY (`id`)',
            'device_id' => 'VARCHAR(500)',
            'token' => 'VARCHAR(500) UNIQUE',
            'meta_information' => 'TEXT',
            'user_id' => 'INT(11)',
            'platform' => 'TEXT',
            'is_subscribed' => 'BOOL',
            'endpoint_arn' => 'VARCHAR(500)',
            'last_activity_at' => 'INT(11) NOT NULL',
            'created_at' => 'INT(11) NOT NULL'
        ]);
    }

    public function down()
    {
        echo "m160118_104139_pushNotifications cannot be reverted.\n";

        return false;
    }

}
