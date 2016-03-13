<?php

namespace item\models\wishListItem;

use api\models\Item;
use Yii;

/**
 * This is the model class for table "WishListItem".
 */
class WishListItemApi extends WishListItem
{
    public function extraFields()
    {
        return ['item'];
    }

    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }
}
