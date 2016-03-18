<?php

use yii\db\Migration;
use yii\db\Schema;


class m160313_131841_wishlists extends Migration
{
    public function up()
    {
        $this->createTable("wish_list_item",[
            'id' => Schema::TYPE_BIGPK,
            'user_id' => Schema::TYPE_INTEGER,
            'item_id' => Schema::TYPE_INTEGER,
            'created_at' => Schema::TYPE_INTEGER,
        ]);

        $this->addForeignKey('fk_wish_list_item_user', 'wish_list_item', 'user_id', 'user', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_wish_list_item_item', 'wish_list_item', 'item_id', 'item', 'id', 'CASCADE', 'NO ACTION');

        
    }



    public function down()
    {
        echo "m160313_131841_wish_list_items cannot be reverted.\n";

        return false;
    }
}
