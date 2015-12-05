<?php

namespace search\components;

use review\models\Review;

class ItemSearchDb
{
    private $client;
    private $index;

    public function __construct()
    {
        $this->client = new \AlgoliaSearch\Client('8M1ZPTMQEW', 'd6e8cc41e0764b2708e9998afd5a139e');
        $this->index = $this->client->initIndex('items');
//        if (YII_ENV == 'prod') {
//        }
//        if (YII_ENV == 'dev') {
//            $this->index = $this->client->initIndex('items_dev');
//        }
//        if (YII_ENV == 'test') {
//            $this->index = $this->client->initIndex('items_test');
//        }
    }

    /**
     * Syncs items with the search database
     * @param $items \item\models\Item[]
     */
    public function sync($items)
    {
        $batch = [];
        foreach ($items as $item) {
            $batch[] = $this->constructItem($item);
        }
        $this->index->saveObjects($batch);
    }

    /**
     * Construct an item array for the search db from an item oject
     * @param \item\models\Item $item
     * @return array
     */
    private function constructItem(\item\models\Item $item)
    {
        $obj = [];

        $obj['objectID'] = $item->id;
        $obj['title'] = $item->name;
        $obj['description'] = $item->description;
        $obj['price_month'] = $item->price_month;
        $obj['price_day'] = $item->price_day;
        $obj['price_week'] = $item->price_week;
        $obj['price_year'] = $item->price_year;
        $obj['_geoloc'] = [
            'lat' => $item->location->latitude,
            'lng' => $item->location->longitude
        ];
        $obj['location']['city'] = $item->location->city;
        $obj['location']['country'] = $item->location->country;
        $obj['hierarchicalCategories_en'] = $this->hierarchicalCat($item, 'en-US');
        $obj['hierarchicalCategories_da'] = $this->hierarchicalCat($item, 'da-DK');
        $obj['img'] = \images\components\ImageHelper::url($item->getImageName(0), ['w' => 500]);
        $obj['review_score'] = (new Review())->computeOverallItemScore($item);
        $obj['owner'] = [
            'id' => $item->owner_id,
            'name' => $item->owner->profile->getName(),
            'img' => $item->owner->profile->getImgUrl()
        ];

        foreach ($item->itemHasItemFacets as $itemHasItemFacet) {
            $obj['facet_' . strtolower($itemHasItemFacet->itemFacet->name) . '_en'][] =
                $itemHasItemFacet->itemFacetValue->getTranslatedName('en-US');
            $obj['facet_' . strtolower($itemHasItemFacet->itemFacet->name) . '_da'][] =
                $itemHasItemFacet->itemFacetValue->getTranslatedName('da-DK');
        }

        return $obj;
    }

    private function hierarchicalCat(\item\models\Item $item, $lang)
    {
        return [
            'lvl0' => $item->category->parent->getTranslatedName($lang),
            'lvl1' => $item->category->parent->getTranslatedName($lang) . " > " . $item->category->getTranslatedName($lang)
        ];
    }
}