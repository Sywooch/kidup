<?php

namespace app\modules\item\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "item_similarity".
 */
class ItemSimilarity extends \app\models\base\ItemSimilarity
{
    private $item;
    /**
     * Computes the similarities for an item
     * @param Item $item
     */
    public function compute($item){
        $this->item = $item;
        $this->removeOld()
            ->insertNewSimilarities();
        return true;
    }

    private function removeOld(){
        ItemSimilarity::deleteAll(['item_id_1' => $this->item->id]);
        return $this;
    }

    private function insertNewSimilarities()
    {
        $distanceQ = 'INSERT INTO item_similarity
        SELECT
            :itemId AS item_id_1,
            id AS item_id_2,
            (similarity_location * 2 + similarity_categories + similarity_price * 0.5) AS similarity,
            similarity_location,
            similarity_categories,
            similarity_price
        FROM (
            SELECT
            item.id,
            similarity_location AS similarity_location,
            cat_score.similarity_categories,
            -- compute price similarity
            CASE
                WHEN item.price_week = :weekPrice THEN 1
                WHEN item.price_week >= (2 * :weekPrice) THEN 0
                WHEN item.price_week > :weekPrice THEN (abs(:weekPrice - item.price_week)/:weekPrice)
                ELSE item.price_week / :weekPrice
            END AS similarity_price
            FROM item
            -- for location we take a max of 50km for now, otherwise location is 0
            INNER JOIN (
                SELECT
                -- lat
               1 - LEAST(50,  6371  * acos( cos( radians( :lat ) )
                        * cos( radians( `location`.`latitude` ) )
                        * cos( radians( `location`.`longitude` ) - radians(:long) )
                        + sin( radians(:lat) )
                        * sin( radians( `location`.`latitude` ) ) ) )/50 AS similarity_location,
                id
                FROM location) loc_score ON (item.location_id = loc_score.id)
            INNER JOIN (
                -- count how many categories are similar in both items
                SELECT
                item_id,
                count(1) / (:catCount) AS similarity_categories
                FROM item_has_category
                WHERE category_id IN (
                    SELECT DISTINCT category_id
                    FROM item_has_category
                    WHERE item_id = :itemId)
                GROUP BY item_id
                ) cat_score ON (cat_score.item_id = item.id)
            ) t
        ORDER BY similarity DESC
        LIMIT 10;';

        Yii::$app->db->createCommand($distanceQ)
            ->bindParam(':itemId', $this->item->id)
            ->bindParam(':lat', $this->item->location->latitude)
            ->bindParam(':long', $this->item->location->longitude)
            ->bindParam(':weekPrice', $this->item->price_week)
            ->bindParam(':catCount', count($this->item->categories))
            ->execute();
        return $this;
    }
}
