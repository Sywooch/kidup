<?php

use yii\db\Migration;

class m150818_133308_sessions extends Migration
{
    public function up()
    {
        $this->createTable('session', [
            'id' => 'CHAR(40) NOT NULL',
            0 => 'PRIMARY KEY (`id`)',
            'expire' => 'INTEGER',
            'data' => 'BLOB',
        ]);
    }

    public function down()
    {
        echo "m150818_133308_sessions cannot be reverted.\n";

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
