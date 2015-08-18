<?php

namespace app\modules\item\models;

use Yii;

/**
 * This is the model class for table "item_similarity".
 */
class ItemSimilarity extends \app\models\base\ItemSimilarity
{
    /**
     * Computes the similarities for an item
     * @param Item $item
     */
    public function compute($item)
    {
        $distanceQ = '
        select * from item

        ( 6371  * acos( cos( radians( ' . floatval($item->location->latitude) . ' ) )
                        * cos( radians( `location`.`latitude` ) )
                        * cos( radians( `location`.`longitude` ) - radians(' . floatval($item->location->longitude) . ') )
                        + sin( radians(' . floatval($item->location->latitude) . ') )
                        * sin( radians( `location`.`latitude` ) ) ) )';


    }
}
