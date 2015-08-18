<?php

use yii\db\Migration;
use yii\db\Schema;

class m150818_153407_itemSimilarity extends Migration
{
    public function up()
    {
        $this->createTable('item_similarity',[
            'item_id_1' => 'INT(11) NOT NULL',
            'item_id_2' => 'INT(11) NOT NULL',
            0 => 'PRIMARY KEY (`item_id_1`, `item_id_2`)',
            'similarity' => Schema::TYPE_DOUBLE,
            'similarity_location' => Schema::TYPE_DOUBLE,
            'similarity_categories' => Schema::TYPE_DOUBLE,
            'similarity_price' => Schema::TYPE_DOUBLE,
        ]);

        $this->createIndex('idx_item_id_1', 'item_similarity', 'item_id_1', 0);
        $this->createIndex('idx_item_id_2', 'item_similarity', 'item_id_2', 0);

        $this->execute('SET foreign_key_checks = 0');
        $this->addForeignKey('fk_item_3407_00', 'item_similarity', 'item_id_1', 'item', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_item_3407_01', 'item_similarity', 'item_id_2', 'item', 'id', 'CASCADE', 'NO ACTION');
        $this->execute('SET foreign_key_checks = 1');
    }

    public function down()
    {
        $this->dropTable('item_similarity');
    }

}
