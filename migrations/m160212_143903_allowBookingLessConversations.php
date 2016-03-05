<?php

use yii\db\Migration;

class m160212_143903_allowBookingLessConversations extends Migration
{
    public function up()
    {
        $this->alterColumn("conversation", "booking_id", "INT null");
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
