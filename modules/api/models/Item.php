<?php

namespace api\models;

use yii\helpers\Json;

/**
 * This is the model class for table "item".
 */
class Item extends \item\models\Item
{

    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            'default' => ['name']
        ]);
    }

    public function fields()
    {
        $fields = parent::fields();

        // remove fields that contain sensitive information
        unset($fields['updated_at'], $fields['min_renting_days']);

        $fields['item_facets'] = function ($model) {
            $res = [];
            $itemFacets = $this->itemHasItemFacets;
            foreach ($itemFacets as $itemFacet) {
                $itemFacet->itemFacet->name = $itemFacet->itemFacet->getTranslatedName();
                $itemFacet->itemFacet->description = $itemFacet->itemFacet->getTranslatedDescription();
                $f = Json::decode(Json::encode($itemFacet->itemFacet));
                if(count($itemFacet->itemFacetValue) > 0){
                    $res[] = array_merge($f, ['value' => $itemFacet->itemFacetValue->getTranslatedName()]);
                }
            }
            return $res;
        };

        $fields['price_day'] = function(){
            return round($this->getDailyPrice());
        };
        $fields['price_week'] = function(){
            return round($this->getWeeklyPrice());
        };
        $fields['price_month'] = function(){
            return round($this->getMonthlyPrice());
        };
        $fields['price_year']  = function(){
            return round($this->getYearlyPrice());
        };

        return $fields;
    }

    public function extraFields()
    {
        return ['owner', 'category', 'location', 'currency', 'media'];
    }

    public function getOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'owner_id']);
    }

    public function getLocation()
    {
        return $this->hasOne(Location::className(), ['id' => 'location_id']);
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }

    public function getMedia()
    {
        return $this->hasMany(Media::className(), ['id' => 'media_id'])
            ->viaTable('item_has_media', ['item_id' => 'id']);
    }

}
