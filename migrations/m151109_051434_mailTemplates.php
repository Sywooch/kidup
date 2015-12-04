<?php

use yii\db\Schema;
use yii\db\Migration;

class m151109_051434_mailTemplates extends Migration
{
    public function up()
    {
        $this->createTable('mail_template',[
            'id' => Schema::TYPE_BIGPK,
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'template' => Schema::TYPE_TEXT,
        ]);
    }

    public function down()
    {
        echo "m151109_051434_mailTemplates cannot be reverted.\n";

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
