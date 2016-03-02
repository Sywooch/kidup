<?php

use yii\db\Migration;

class m151011_133145_yearlyPrices extends Migration
{
    public function up()
    {
        $this->addColumn('item', 'price_year', \yii\db\Schema::TYPE_INTEGER . " NULL AFTER `price_month`");
    }

    public function down()
    {
        echo "m151011_133145_yearlyPrices cannot be reverted.\n";

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
