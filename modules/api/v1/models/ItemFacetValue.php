<?php

namespace api\v1\models;

/**
 * This is the model class for table "item".
 */
class ItemFacetValue extends  \item\models\itemFacetValue\ItemFacetValue
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
