<?php

use yii\db\Migration;
use app\models\base\Category;

class m150913_090209_newCategories extends Migration
{
    public function up()
    {
        $mysql = "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        $this->execute('SET foreign_key_checks = 0;');

        $this->dropColumn('category', 'type');
        $this->dropColumn('item', 'condition');
        $this->dropTable('item_has_category');
        $this->addColumn('category', 'parent_id', \yii\db\Schema::TYPE_INTEGER . " NULL");
        $this->addColumn('item', 'category_id', \yii\db\Schema::TYPE_INTEGER . " NOT NULL");

        $this->createIndex('idx_category_parent_1', 'category', 'parent_id', 0);

        $this->createTable('category_tag', [
            'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
            'language_id' => 'VARCHAR(5) NOT NULL',
            'tag' => 'VARCHAR(45) NOT NULL',
            0 => 'PRIMARY KEY (`id`)',
            'category_id' => 'INT(11) NOT NULL',
        ], $mysql);

        $this->createIndex('fk_category_tag_language1_idx', 'category_tag', 'language_id', 0);
        $this->createIndex('fk_category_tag_category1_idx', 'category_tag', 'category_id', 0);

        $this->addForeignKey('fk_category_tag_language1', 'category_tag', 'language_id', 'language', 'language_id',
            'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_category_tag_category1', 'category_tag', 'category_id', 'category', 'id', 'CASCADE',
            'NO ACTION');
        $this->addForeignKey('fk_category_8421_01', 'category', 'parent_id', 'category', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_item_category_8765_01', 'item', 'category_id', 'category', 'id', 'CASCADE',
            'NO ACTION');

        $this->createTable('feature', [
            'id' => 'INT NOT NULL AUTO_INCREMENT',
            'is_singular' => 'TINYINT NOT NULL',
            'name' => 'VARCHAR(45) NOT NULL',
            'description' => 'VARCHAR(256) NULL',
            'is_required' => 'TINYINT NOT NULL',
            0 => 'PRIMARY KEY (`id`)',
        ], $mysql);

        $this->createTable('feature_value', [
            'id' => 'INT NOT NULL AUTO_INCREMENT',
            'feature_id' => 'INT NOT NULL',
            'name' => 'VARCHAR(45) NOT NULL',
            0 => 'PRIMARY KEY (`id`)',
        ], $mysql);

        $this->createTable('category_has_feature', [
            'category_id' => 'INT NOT NULL AUTO_INCREMENT',
            'feature_id' => 'INT NOT NULL',
            0 => 'PRIMARY KEY (`category_id`, `feature_id`)',
        ], $mysql);

        $this->createTable('item_has_feature', [
            'item_id' => 'INT NOT NULL AUTO_INCREMENT',
            'feature_id' => 'INT NOT NULL',
            'feature_values_id' => 'INT NOT NULL',
            0 => 'PRIMARY KEY (`item_id`, `feature_id`)',
        ], $mysql);

        $this->createTable('item_has_feature_singular', [
            'item_id' => 'INT NOT NULL AUTO_INCREMENT',
            'feature_id' => 'INT NOT NULL',
            0 => 'PRIMARY KEY (`item_id`, `feature_id`)',
        ], $mysql);

        $this->createIndex('fk_feature_value_feature1_idx', 'feature_value',
            'feature_id', 0);
        $this->addForeignKey('fk_feature_values_feature1', 'feature_value',
            'feature_id', 'feature', 'id', 'CASCADE', 'NO ACTION');

        $this->createIndex('fk_category_has_feature_feature1_idx', 'category_has_feature',
            'feature_id', 0);
        $this->createIndex('fk_category_has_feature_category1_idx', 'category_has_feature',
            'category_id', 0);
        $this->addForeignKey('fk_category_has_feature_category1', 'category_has_feature',
            'category_id', 'category', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_category_has_feature_feature1', 'category_has_feature',
            'feature_id', 'feature', 'id', 'CASCADE', 'NO ACTION');

        $this->createIndex('fk_item_has_feature_feature1_idx', 'item_has_feature',
            'feature_id', 0);
        $this->createIndex('fk_item_has_feature_item1_idx', 'item_has_feature', 'item_id', 0);
        $this->createIndex('fk_item_has_feature_feature_values1_idx', 'item_has_feature',
            'feature_values_id', 0);

        $this->addForeignKey('fk_item_has_feature_item1', 'item_has_feature', 'item_id', 'item',
            'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_item_has_feature_feature1', 'item_has_feature',
            'feature_id', 'feature', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_item_has_feature_feature_values1', 'item_has_feature',
            'feature_values_id', 'feature_value', 'id', 'CASCADE', 'NO ACTION');

        $this->createIndex('fk_item_has_feature1_feature1_idx',
            'item_has_feature_singular', 'feature_id', 0);
        $this->createIndex('fk_item_has_feature1_item1_idx', 'item_has_feature_singular', 'item_id',
            0);

        $this->addForeignKey('fk_item_has_feature1_item1', 'item_has_feature_singular', 'item_id',
            'item', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_item_has_feature1_feature1', 'item_has_feature_singular',
            'feature_id', 'feature', 'id', 'CASCADE', 'NO ACTION');

        $this->createTable('item_search', [
            'component_type' => 'VARCHAR(10) NOT NULL',
            'component_id' => 'INT NOT NULL',
            'text' => 'VARCHAR(45) NOT NULL',
            'item_id' => 'INT NOT NULL',
            'language_id' => 'VARCHAR(5) NOT NULL',
        ], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=MyISAM");

        $this->createIndex('fk_item_search_item1_idx', 'item_search', 'item_id', 0);
        $this->createIndex('fk_item_search_language1_idx', 'item_search', 'language_id', 0);
        $this->execute("ALTER TABLE `item_search` ADD FULLTEXT INDEX `text` (`text`);");

        $this->addForeignKey('fk_item_search_item1', 'item_search', 'item_id', 'item', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_item_search_language1', 'item_search', 'language_id', 'language', 'id', 'CASCADE',
            'NO ACTION');

        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        echo "m150913_090209_newCategories cannot be reverted.\n";
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
