<?php
namespace notification\components\renderer;

use item\models\Item;
use notification\components\Renderer;

class ItemRenderer
{

    /**
     * Load all item render variables.
     *
     * @param Item $item The item.
     * @return array All the render variables.
     */
    public function loadItem(Item $item) {
        $result = [];

        // Payout
        $result['item_name'] = $item->name;

        return $result;
    }

}