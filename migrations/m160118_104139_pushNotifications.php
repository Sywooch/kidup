<?php

use yii\db\Schema;
use yii\db\Migration;

class m160118_104139_pushNotifications extends Migration
{
    public function up()
    {
        $this->createTable('mobile_devices', [
            'id' => 'CHAR(40) NOT NULL',
            0 => 'PRIMARY KEY (`id`)',
            'device_id' => 'TEXT',
            'token' => 'TEXT',
            'meta_information' => 'TEXT',
            'user_id' => 'INT(11)',
            'platform' => 'ENUM("apns", "gcm")'
        ]);
    }

    public function down()
    {
        echo "m160118_104139_pushNotifications cannot be reverted.\n";

        return false;
    }

}
