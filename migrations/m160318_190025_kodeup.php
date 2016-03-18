<?php

use yii\db\Migration;
use yii\db\Schema;

class m160318_190025_kodeup extends Migration
{
    public function up()
    {
        $this->createTable("kodeup", [
            'id' => Schema::TYPE_BIGPK,
            'device_id' => Schema::TYPE_STRING,
            'image_url' => Schema::TYPE_STRING,
            'rating' => Schema::TYPE_SMALLINT
        ]);
    }

    public function down()
    {
        echo "m160318_190025_kodeup cannot be reverted.\n";

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
