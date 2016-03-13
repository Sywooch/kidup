<?php
namespace codecept\muffins;

use item\models\wishListItem\WishListItem;
use Faker\Factory as Faker;

class WishListItemMuffin extends WishListItem
{
    public function definitions()
    {
        $faker = Faker::create();
        return [
            'user_id' => 'factory|'.UserMuffin::class,
            'item_id' => 'factory|'.ItemMuffin::class,
            'created_at' => time(),
        ];
    }
}
