<?php

use yii\db\Schema;
use yii\db\Migration;

class m150926_184727_dbTranslations extends Migration
{
    public function up()
    {
        $this->createTable('i18n_source',[
            'id' => Schema::TYPE_BIGPK,
            'category' => Schema::TYPE_STRING,
            'message' => Schema::TYPE_TEXT,
        ]);

        $this->createTable('i18n_message',[
            'id' => Schema::TYPE_BIGINT,
            'language' => Schema::TYPE_STRING,
            0 => 'PRIMARY KEY (`id`, `language`)',
            'translation' => Schema::TYPE_TEXT,
        ]);

        $this->execute('SET foreign_key_checks = 0;');
        $this->addForeignKey('fk_i18n_source_i18n_message', 'i18n_message', 'id', 'i18n_source', 'id', 'CASCADE', 'NO ACTION');
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        echo "m150926_184727_dbTranslations cannot be reverted.\n";

        return false;
    }
}
