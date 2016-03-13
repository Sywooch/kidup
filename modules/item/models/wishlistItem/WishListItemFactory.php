<?php

namespace item\models\wishListItem;

use item\models\item\Item;
use user\models\User;
use Yii;

/**
 * This is the model class for table "WishListItem".
 */
class WishListItemFactory
{

    public function create(User $user, Item $item)
    {
        $wishListItem = WishListItem::find()->where([
            'user_id' => $user->id,
            'item_id' => $item->id
        ])->one();
        if ($wishListItem !== null) {
            return $wishListItem;
        }
        $wishListItem = new WishListItem();
        $wishListItem->created_at = time();
        $wishListItem->user_id = $user->id;
        $wishListItem->item_id = $item->id;
        $wishListItem->save();
        return $wishListItem;
    }
}
