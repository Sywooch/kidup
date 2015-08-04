<?php
namespace app\modules\search2\models;

use app\modules\item\models\Category;
use app\modules\item\models\Item;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * The item model of the search module is used for handling data related to searching items.
 *
 * Class ItemModel
 * @package app\modules\search\models
 * @author kevin91nl
 */
class SearchModel extends Model
{

    public $query = null;
    public $location = null;
    public $categories = [];
    public $_distanceSliderIndex = 1;

    public function loadParameters($params)
    {
        $this->parseQueryString($params);
    }

    public function findItems()
    {
        $query = Item::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        $query->select('`item`.*');

        /*$distanceQ = '( 6371  * acos( cos( radians( ' . floatval($this->latitude) . ' ) )
                    * cos( radians( `location`.`latitude` ) )
                    * cos( radians( `location`.`longitude` ) - radians(' . floatval($this->longitude) . ') )
                    + sin( radians(' . floatval($this->latitude) . ') )
                    * sin( radians( `location`.`latitude` ) ) ) )';

        $query->select($distanceQ . ' as distance, `item`.*');
        $query->orderBy('distance');
        $query->innerJoinWith(['location', 'itemHasCategories']);*/

//        if(isset($this->distance)){
//            $query->andWhere('distance < :meters', [':meters' => $this->convertDistanceInternal($this->distance)]);
//        }

        /*if(isset($this->categories)){
            foreach ($this->categories as $id) {
                $query->andWhere('category_id = :id', [':id' => $id]);
            }
        }

        if (isset($this->priceMin) && isset($this->priceMax)) {
            $query->andWhere('price_week > :low and price_week < :high', [
                ':low' => $this->priceMin,
                ':high' => $this->priceMax
            ]);
        }

        $query->andWhere(['is_available' => 1]);*/

        // search for the query
        if ($this->query !== null) {
            $query->andWhere(['LIKE', 'name', $this->query]);
        }

        return $dataProvider;
    }

    public function getCategories($type)
    {
        return Category::find()->where(['type' => $type])->all();
    }

    public function parseQueryString($query)
    {
        $params = [];
        $parts = explode('|', $query);
        foreach ($parts as $i => $part) {
            if (isset($parts[$i + 1]) && property_exists($this, $part)) {
                $params[$part] = $parts[$i + 1];
            }
            continue;
        }

        return $params;
    }
}