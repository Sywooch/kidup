<?php

use yii\db\Migration;
use item\models\base\Category;
use item\models\base\FeatureValue;
use item\models\base\Feature;
use yii\helpers\ArrayHelper;
use item\models\base\CategoryHasFeature;

class m150913_090209_newCategories extends Migration
{
    public function up()
    {
        $mysql = "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        $this->execute('SET foreign_key_checks = 0;');

        $this->dropColumn('category', 'type');
        $this->dropColumn('item', 'condition');
        $this->dropTable('item_has_category');
        $this->dropTable('child');
        $this->addColumn('category', 'parent_id', \yii\db\Schema::TYPE_INTEGER . " NULL");
        $this->addColumn('item', 'category_id', \yii\db\Schema::TYPE_INTEGER . " NOT NULL");

        $this->createIndex('idx_category_parent_1', 'category', 'parent_id', 0);

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
            'feature_value_id' => 'INT NOT NULL',
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
            'feature_value_id', 0);

        $this->addForeignKey('fk_item_has_feature_item1', 'item_has_feature', 'item_id', 'item',
            'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_item_has_feature_feature1', 'item_has_feature',
            'feature_id', 'feature', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_item_has_feature_feature_values1', 'item_has_feature',
            'feature_value_id', 'feature_value', 'id', 'CASCADE', 'NO ACTION');

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
            'language_id' => 'VARCHAR(5) NOT NULL',
        ], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=MyISAM");

        $this->createIndex('fk_item_search_language1_idx', 'item_search', 'language_id', 0);
        $this->execute("ALTER TABLE `item_search` ADD FULLTEXT INDEX `text` (`text`);");

        $this->addForeignKey('fk_item_search_language1', 'item_search', 'language_id', 'language', 'id', 'CASCADE',
            'NO ACTION');

        $this->execute('SET foreign_key_checks = 0;');
        $this->newCategories();
        $this->transportExistingItems();
        $this->execute('SET foreign_key_checks = 1;');
    }


    private function transportExistingItems(){
        $items = \item\models\base\Item::find()->all();
        foreach ($items as $item) {
            $item->category_id = 1;
            $item->save();

            $ihfs = new \item\models\base\ItemHasFeatureSingular();
            $ihfs->item_id = $item->id;
            $ihfs->feature_id = 8;
            $ihfs->save();

            $ihfs = new \item\models\base\ItemHasFeatureSingular();
            $ihfs->item_id = $item->id;
            $ihfs->feature_id = 9;
            $ihfs->save();

            $ihfs = new \item\models\base\ItemHasFeature();
            $ihfs->item_id = $item->id;
            $ihfs->feature_id = 4;
            $ihfs->feature_value_id = rand(31,32);
            $ihfs->save();

            $ihfs = new \item\models\base\ItemHasFeature();
            $ihfs->item_id = $item->id;
            $ihfs->feature_id = 7;
            $ihfs->feature_value_id = 41;
            $ihfs->save();
        }
    }

    private function newCategories(){
        \item\models\base\FeatureValue::deleteAll();
        Feature::deleteAll();

        $babySizes = new Feature();
        $babySizes->setAttributes([
            'name' => "Size",
            'is_singular' => 0,
            'description' => "Baby cloth size",
            'is_required' => 0,
        ]);
        $babySizes->save();

        for ($i = 50; $i <= 86; $i += 4) {
            $f = new FeatureValue();
            $f->setAttributes([
                'feature_id' => $babySizes->id,
                'name' => "Size " . $i
            ]);
            $f->save();
        }

        $childSizes = new Feature();
        $childSizes->setAttributes([
            'name' => "Size",
            'is_singular' => 0,
            'description' => "Children cloth size",
            'is_required' => 0,
        ]);
        $childSizes->save();

        for ($i = 92; $i <= 176; $i += 6) {
            $f = new FeatureValue();
            $f->setAttributes([
                'feature_id' => $childSizes->id,
                'name' => "Size " . $i
            ]);
            $f->save();
        }

        $clothBrands = new Feature();
        $clothBrands->setAttributes([
            'name' => "Brand",
            'is_singular' => 0,
            'description' => "Brand of the Stroller",
            'is_required' => 0,
        ]);
        $clothBrands->save();
        foreach (["Maxi-Cosi", "Noppies", "Pampers"] as $clothBrand) {
            $f = new FeatureValue();
            $f->setAttributes([
                'feature_id' => $clothBrands->id,
                'name' => $clothBrand
            ]);
            $f->save();
        }

        $condition = new Feature();
        $condition->setAttributes([
            'name' => "Condition",
            'is_singular' => 0,
            'description' => "Condition of the item",
            'is_required' => 1,
        ]);
        $condition->save();

        foreach (["New", "As Good as New", "Used"] as $status) {
            $f = new FeatureValue();
            $f->setAttributes([
                'feature_id' => $condition->id,
                'name' => $status
            ]);
            $f->save();
        }

        $genders = new Feature();
        $genders->setAttributes([
            'name' => "Gender",
            'is_singular' => 0,
            'description' => "Gender",
            'is_required' => 0,
        ]);
        $genders->save();

        foreach (["Boy", "Girl", "Boy or Girl"] as $gender) {
            $f = new FeatureValue();
            $f->setAttributes([
                'feature_id' => $genders->id,
                'name' => $gender
            ]);
            $f->save();
        }

        $strollerBrands = new Feature();
        $strollerBrands->setAttributes([
            'name' => "Brand",
            'is_singular' => 0,
            'description' => "Brand of the Stroller",
            'is_required' => 0,
        ]);
        $strollerBrands->save();

        foreach (["Maxi-Cosi", "Quinny", "Bugaboo", "Mutsy"] as $strollerBrand) {
            $f = new FeatureValue();
            $f->setAttributes([
                'feature_id' => $strollerBrands->id,
                'name' => $strollerBrand
            ]);
            $f->save();
        }

        $exchangeFeature = new Feature();
        $exchangeFeature->setAttributes([
            'name' => "Exchange",
            'is_singular' => 0,
            'description' => "Prefered method for the exchange",
            'is_required' => 1,
        ]);
        $exchangeFeature->save();

        foreach (["Pickup", "Delivery Possible"] as $exchange) {
            $f = new FeatureValue();
            $f->setAttributes([
                'feature_id' => $exchangeFeature->id,
                'name' => $exchange
            ]);
            $f->save();
        }

        $generalFeatures = [];

        foreach ([
                     [
                         "Smoke Free",
                         'Product is used in a smoke-free environment only'
                     ],
                     [
                         "Pet Free",
                         'Product is used in a pet-free environment only'
                     ]
                 ] as $generalFeature) {
            $gf = new Feature();
            $gf->setAttributes([
                'is_singular' => 1,
                'name' => $generalFeature[0],
                'description' => $generalFeature[1],
                'is_required' => 0,
            ]);
            $gf->save();
            $generalFeatures[] = $gf;
        }

        Category::deleteAll();

        $categories = [
            "Baby Clothes" => [
                'features' => [
                    $clothBrands,
                    $babySizes,
                    $genders
                ],
                'subcats' => [
                    "Onesies & Baby Bodysuits",
                    "Trousers & Jeans",
                    "Dresses & Skirts",
                    "T-Shirts & Tops",
                    "Sweaters",
                ]
            ],
            "Baby Necessities" => [
                'features' => [],
                'subcats' => [
                    "Baby Monitors",
                    "Cots & Cribs",
                    "Baths & Care",
                    "Blankets & Sleeping Bags",
                ]
            ],
            "On the Road" => [
                'features' => [

                ],
                'subcats' => [
                    "Car Seats",
                    "Buggies",
                    "Baby Carriers",
                    "Strollers" => [
                        $strollerBrands
                    ]
                ]
            ],
            "Children's Clothes" => [
                'features' => [
                    $clothBrands,
                    $childSizes,
                    $genders
                ],
                'subcats' => [
                    "Accessories",
                    "Trousers & Jeans",
                    "Dresses & Skirts",
                    "Clothing Sets",
                    "Underwear",
                    "T-Shirts & Tops",
                    "Sweaters",
                ]
            ],
            "Children's Furniture" => [
                'features' => [],
                'subcats' => [
                    "Beds",
                    "Furnishing & Decoration",
                    "High Chairs",
                    "Bunks & Loft Beds",
                ]
            ],
            "Toys" => [
                'features' => [],
                'subcats' => [
                    "Baby Toys",
                    "Duplo & Lego",
                    "Wooden Toys",
                    "Dolls",
                    "Puzzles",
                    "Race Tracks",
                ]
            ],
            "Toys Outside" => [
                'features' => [],
                'subcats' => [
                    "Action Toys",
                    "Playhouses",
                    "Trampolines & Bouncy Castles",
                    "Vehicles & Bikes"
                ]
            ]
        ];
        $idCounter = 1;
        foreach ($categories as $mainCat => $subCats) {
            $c = new Category();
            $c->name = $mainCat;
            $c->id = $idCounter;
            $c->save();

            $idCounter++;

            foreach ($subCats['subcats'] as $subCat => $features) {
                if (!is_int($subCat)) {
                    $name = $subCat;
                } else {
                    $name = $features;
                }
                $c2 = new Category();
                $c2->name = $name;
                $c2->parent_id = $c->id;
                $c2->id = $idCounter;
                $c2->save();
                $idCounter++;

                $features = ArrayHelper::merge([$condition, $exchangeFeature], $generalFeatures, $subCats['features']);

                foreach ($features as $feature) {
                    $chf = new CategoryHasFeature();
                    $chf->setAttributes([
                        'category_id' => $c2->id,
                        'feature_id' => $feature->id
                    ]);
                    $chf->save();
                }
            }
        }
    }

    public function down()
    {
        echo "m150913_090209_newCategories cannot be reverted.\n";
        return false;
    }
}
