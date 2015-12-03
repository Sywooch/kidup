<?php

namespace api\models;

use yii\helpers\Json;

/**
 * This is the model class for table "item".
 */
class Item extends \item\models\Item
{
    public function fields()
    {
        $fields = parent::fields();

        // remove fields that contain sensitive information
        unset($fields['updated_at'], $fields['min_renting_days']);

        $fields['features'] = function ($model) {
            $res = [];
            $features = $this->itemHasFeatures;
            foreach ($features as $feature) {
                $f = Json::decode(Json::encode($feature->feature));
                $res[] = array_merge($f, ['value' => $feature->featureValue->getTranslatedName()]);
            }
            $features = $this->itemHasFeatureSingulars;
            foreach ($features as $feature) {
                $f = Json::decode(Json::encode($feature->feature));
                $res[] = array_merge($f, ['value' => true]);
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
