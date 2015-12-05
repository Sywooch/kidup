<?php

use yii\db\Migration;
use yii\db\Schema;

class m151205_074128_moreAnalytics extends Migration
{
    public function up()
    {
        $this->addColumn("tracking_event", "language", Schema::TYPE_STRING);
        $this->addColumn("tracking_event", "source", Schema::TYPE_SMALLINT);
        $this->addColumn("tracking_event", "ip", Schema::TYPE_STRING);
        $this->addColumn("tracking_event", "is_mobile", Schema::TYPE_BOOLEAN);
        $this->dropColumn("tracking_event", 'device');
        $this->alterColumn("tracking_event", "data", Schema::TYPE_TEXT);
    }

    public function down()
    {
        echo "m151205_074128_moreAnalytics cannot be reverted.\n";

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
