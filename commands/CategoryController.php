<?php

namespace app\commands;

use app\models\base\Category;
use app\models\base\CategoryHasFeature;
use app\models\base\Feature;
use app\models\base\FeatureValue;
use app\modules\item\models\Item;
use app\modules\search\models\ItemSearch;
use Yii;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

class CategoryController extends Controller
{

    public function actionFeatures()
    {
        FeatureValue::deleteAll();
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
        foreach (["Maxi-Cosi", "Noppies", "Pampers", "Petit Bateau", "Philips Avent"] as $clothBrand) {
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

        foreach (["Pickup", "Delivery up to 1 km", "Delivery up to 5 km", "Delivery up to 10 km"] as $exchange) {
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

        foreach ($categories as $mainCat => $subCats) {
            $c = new Category();
            $c->name = $mainCat;
            $c->save();

            foreach ($subCats['subcats'] as $subCat => $features) {
                if (!is_int($subCat)) {
                    $name = $subCat;
                } else {
                    $name = $features;
                }
                $c2 = new Category();
                $c2->name = $name;
                $c2->parent_id = $c->id;
                $c2->save();

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

    public function actionGenerate(){
        $items = Item::find()->all();
        $categories = Category::find()->where('parent_id IS NOT NULL')->all();
        foreach ($items as $item) {
            $item->category_id = $categories[rand(0, count($categories) - 1)]->id;
            $item->save();
            ItemSearch::updateSearch($item);
        }

    }

}

