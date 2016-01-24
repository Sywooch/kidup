<?php

use yii\db\Schema;
use yii\db\Migration;

class m160120_102554_addSingularItemFacets extends Migration
{
    public function up()
    {
//        $this->dropTable("item_has_feature_singular");
        $this->alterColumn("item_has_item_facet", "item_facet_value_id", "INT null");
    }

    public function down()
    {
        echo "m160120_102554_addSingularItemFacets cannot be reverted.\n";

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
