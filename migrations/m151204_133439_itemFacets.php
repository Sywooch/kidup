<?php

use yii\db\Migration;
use yii\db\Schema;

class m151204_133439_itemFacets extends Migration
{
    public function up()
    {
        $this->dropTable("user_has_promotion_code");
        $this->dropTable("promotion_code");
        $this->renameTable("category_has_feature", "category_has_item_facet");
        $this->renameTable("feature", "item_facet");
        $this->renameTable("feature_value", "item_facet_value");
        $this->renameTable("item_has_feature", "item_has_item_facet");

        $this->execute('SET foreign_key_checks = 0;');
        $this->dropForeignKey('fk_feature_values_feature1', 'item_facet_value');

        $this->dropForeignKey('fk_category_has_feature_category1', 'category_has_item_facet');
        $this->dropForeignKey('fk_category_has_feature_feature1', 'category_has_item_facet');

        $this->dropForeignKey('fk_item_has_feature_item1', 'item_has_item_facet');
        $this->dropForeignKey('fk_item_has_feature_feature1', 'item_has_item_facet');
        $this->dropForeignKey('fk_item_has_feature_feature_values1', 'item_has_item_facet');

        $this->renameColumn("category_has_item_facet", "feature_id", "item_facet_id");
        $this->renameColumn("item_has_item_facet", "feature_id", "item_facet_id");
        $this->renameColumn("item_has_item_facet", "feature_value_id", "item_facet_value_id");
        $this->renameColumn("item_facet_value", "feature_id", "item_facet_id");
        $this->renameColumn("item_facet", "is_singular", "allow_multiple");
        $this->alterColumn("item_facet", "allow_multiple", Schema::TYPE_BOOLEAN);
        $this->addColumn("item_has_item_facet", "created_at", Schema::TYPE_INTEGER);
        $this->addColumn("item_has_item_facet", "updated_at", Schema::TYPE_INTEGER);

        $this->addForeignKey('fk_item_facet_values_item_facet1', 'item_facet_value',
            'item_facet_id', 'item_facet', 'id', 'CASCADE', 'NO ACTION');

        $this->addForeignKey('fk_category_has_item_facet_category1', 'category_has_item_facet',
            'category_id', 'category', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_category_has_item_facet_item_facet1', 'category_has_item_facet',
            'item_facet_id', 'item_facet', 'id', 'CASCADE', 'NO ACTION');

        $this->addForeignKey('fk_item_has_item_facet_item1', 'item_has_item_facet', 'item_id', 'item',
            'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_item_has_item_facet_item_facet1', 'item_has_item_facet',
            'item_facet_id', 'item_facet', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_item_has_item_facet_item_facet_values1', 'item_has_item_facet',
            'item_facet_value_id', 'item_facet_value', 'id', 'CASCADE', 'NO ACTION');

        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        echo "m151204_133439_itemFacets cannot be reverted.\n";

        return false;
    }
}
