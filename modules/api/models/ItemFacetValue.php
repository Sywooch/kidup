<?php

namespace api\models;

/**
 * This is the model class for table "item".
 */
class ItemFacetValue extends \item\models\base\ItemFacetValue
{
    public function extraFields()
    {
        return ['itemFacet'];
    }

    public function getItemFacet()
    {
        return $this->hasOne(ItemFacet::className(), ['id' => 'owner_id']);
    }
}
