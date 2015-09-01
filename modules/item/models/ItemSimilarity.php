<?php

namespace app\modules\item\models;

use Yii;
use yii\db\Query;

/**
 * This is the base-model class for table "item_similarity".
 *
 * @property integer $item_id_1
 * @property integer $item_id_2
 * @property double $similarity
 * @property double $similarity_location
 * @property double $similarity_categories
 * @property double $similarity_price
 *
 * @property \app\models\Item $originatingItem
 * @property \app\models\Item $similarItem
 */
class ItemSimilarity extends \app\models\base\ItemSimilarity
{
    public $item;

    /**
     * Computes the similarities for an item
     * @param Item $item
     */
    public function compute(Item $item)
    {
        $this->item = $item;
        $this->removeOld();
        $this->insertNewSimilarities();
        $this->removeItself();
        return true;
    }

    private function removeOld()
    {
        return ItemSimilarity::deleteAll(['item_id_1' => $this->item->id]);
    }

    private function removeItself()
    {
        return ItemSimilarity::deleteAll(['item_id_1' => $this->item->id, 'item_id_2' => $this->item->id]);
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


        // this is ugly, but gives some property overloading error if not done like this

        $id = $this->item->id;
        $lat = $this->item->location->latitude;
        $long = $this->item->location->longitude;
        $price = $this->item->price_week;
        $categories = count($this->item->categories);

        return Yii::$app->db->createCommand($distanceQ)
            ->bindParam(':itemId', $id)
            ->bindParam(':lat', $lat)
            ->bindParam(':long', $long)
            ->bindParam(':weekPrice', $price)
            ->bindParam(':catCount', $categories)
            ->execute();
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOriginatingItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id_2']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSimilarItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id_2']);
    }
}
