<?php

use yii\db\Schema;
use yii\db\Migration;

class m160212_143903_allowBookingLessConversations extends Migration
{
    public function up()
    {
        $this->addColumn("tracking_event", "device_uuid", "VARCHAR(255) null");
    }

    public function down()
    {
        echo "m160212_143903_allowBookingLessConversations cannot be reverted.\n";

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
