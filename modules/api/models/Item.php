<?php

namespace api\models;

/**
 * This is the model class for table "item".
 */
class Item extends \item\models\Item
{
    public function fields()
    {
        $fields = parent::fields();

        // remove fields that contain sensitive information
        unset($fields['created_at'], $fields['updated_at'], $fields['min_renting_days']);

        return $fields;
    }

    public function extraFields()
    {
        return ['owner', 'category', 'location', 'currency', 'media'];
    }

    public function getOwner(){
        return $this->hasOne(User::className(), ['id' => 'owner_id']);
    }

    public function getLocation(){
        return $this->hasOne(Location::className(), ['id' => 'location_id']);
    }

    public function getCategory(){
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getCurrency(){
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }

    public function getMedia()
    {
        return $this->hasMany(Media::className(), ['id' => 'media_id'])
            ->viaTable('item_has_media', ['item_id' => 'id']);
    }
}
