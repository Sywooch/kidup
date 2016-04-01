<?php

use yii\db\Migration;

class m160401_111627_log_table_separation extends Migration
{
    public function up()
    {
        $this->renameTable("log", "log_application");

        $this->createTable('log_error', [
            'id' => $this->bigPrimaryKey(),
            'level' => $this->integer(),
            'category' => $this->string(),
            'log_time' => $this->double(),
            'prefix' => $this->text(),
            'message' => $this->text(),
        ]);

        $this->createIndex('idx_log_error_level', 'log_error', 'level');
        $this->createIndex('idx_log_error_category', 'log_error', 'category');

        $this->alterColumn("log_error", 'log_time', \yii\db\Schema::TYPE_INTEGER);
    }

    public function down()
    {
        echo "m160401_111627_log_table_separation cannot be reverted.\n";

        return false;
    }
}
