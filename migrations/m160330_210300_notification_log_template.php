<?php

use yii\db\Migration;
use yii\db\Schema;

class m160330_210300_notification_log_template extends Migration
{
    public function up()
    {
        $this->addColumn("notification_mail_log", "template", Schema::TYPE_STRING);
        $this->addColumn("notification_push_log", "template", Schema::TYPE_STRING);
    }

    public function down()
    {
        echo "m160330_210300_notification_log_template cannot be reverted.\n";

        return false;
    }

}
