<?php

use yii\db\Migration;
use yii\db\Schema;

class m160330_212154_enhance_notification_log extends Migration
{
    public function up()
    {
        $this->alterColumn("notification_mail_log", "view", 'VARCHAR(10000)');
        $this->alterColumn("notification_push_log", "view", 'VARCHAR(10000)');
        $this->alterColumn("notification_mail_log", "variables", 'VARCHAR(10000)');
        $this->alterColumn("notification_push_log", "variables", 'VARCHAR(10000)');
    }

    public function down()
    {
        echo "m160330_212154_enhance_notification_log cannot be reverted.\n";

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
