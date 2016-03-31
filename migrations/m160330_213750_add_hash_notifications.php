<?php

use yii\db\Migration;
use yii\db\Schema;

class m160330_213750_add_hash_notifications extends Migration
{
    public function up()
    {
        $this->addColumn("notification_mail_log", "hash", Schema::TYPE_STRING);
        $this->addColumn("notification_push_log", "hash", Schema::TYPE_STRING);
    }

    public function down()
    {
        echo "m160330_213750_add_hash_notifications cannot be reverted.\n";

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
