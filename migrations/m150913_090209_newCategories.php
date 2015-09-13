<?php

use yii\db\Migration;
use app\models\base\Category;

class m150913_090209_newCategories extends Migration
{
    public function up()
    {
//        $this->dropColumn('category', 'type');
//        $this->dropTable('item_has_category');
//        $this->addColumn('category', 'parent_id', \yii\db\Schema::TYPE_INTEGER." NULL");
//        $this->addColumn('item', 'category_id', \yii\db\Schema::TYPE_INTEGER." NOT NULL");
//
//        $this->createIndex('idx_category_parent_1', 'category', 'parent_id', 0);
//
//        $this->execute('SET foreign_key_checks = 0;');
//        $this->addForeignKey('fk_category_8421_01', 'category', 'parent_id', 'category', 'id', 'CASCADE', 'NO ACTION');
//        $this->addForeignKey('fk_item_category_8765_01', 'item', 'category_id', 'category', 'id', 'CASCADE', 'NO ACTION');
//        $this->execute('SET foreign_key_checks = 1;');

        Category::deleteAll();

        $categories = [
            "Baby Clothes" => [
                "Onesies & Baby Bodysuits",
                "Trousers & Jeans",
                "Dresses & Skirts",
                "T-Shirts & Tops",
                "Sweaters",
            ],
            "Baby Necessities" => [
                "Baby Monitors",
                "Cots & Cribs",
                "Baths & Care",
                "Blankets & Sleeping Bags",
            ],
            "On the Road" => [
                "Car Seats",
                "Buggies",
                "Baby Carriers",
                "Strollers"
            ],
            "Children's Clothes" => [
                "Accessories",
                "Trousers & Jeans",
                "Dresses & Skirts",
                "Clothing Sets",
                "Underwear",
                "T-Shirts & Tops",
                "Sweaters",
            ],
            "Children's Furniture" => [
                "Beds",
                "Furnishing & Decoration",
                "High Chairs",
                "Bunks & Loft Beds",
            ],
            "Toys" => [
                "Baby Toys",
                "Duplo & Lego",
                "Wooden Toys",
                "Dolls",
                "Puzzles",
                "Race Tracks",
            ],
            "Toys Outside" => [
                "Action Toys",
                "Playhouses",
                "Trampolines & Bouncy Castles",
                "Vehicles and Bikes"
            ]
        ];

        foreach ($categories as $mainCat => $subCats) {
            $c = new Category();
            $c->name = $mainCat;
            $c->save();
            foreach ($subCats as $subCat) {
                $c2 = new Category();
                $c2->name = $subCat;
                $c2->parent_id = $c->id;
                $c2->save();
            }
        }
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
