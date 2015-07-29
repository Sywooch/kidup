<?php

namespace app\modules\item\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;

/**
 * Class Search
 *
 * A search model used for
 *
 * @package app\modules\item\models
 */
class Search extends Model
{
    public $query = '';
    public $location = '';
    public $distance = -1;
    public $distanceIndex = 0;
    public $priceMin = 0;
    public $priceMax = 999;
    public $categories = [];
    public $age = [];
    public $priceRange = '0,999';
    private $latitude;
    private $longitude;

    /**
     * Load and set the default parameters for the search.
     *
     * @param array $params parameters to load
     */
    public function __construct($params)
    {
        if (isset($params['query'])) {
            $this->query = $params['query'];
        }

        if (!isset($params['q'])) {
            return;
        }

        $query = $params['q'];
        $parts = explode('|', $query);
        $index = 0;
        $variable = null;
        $value = null;
        foreach ($parts as $item) {
            if ($index % 2 == 0) {
                // even = variable
                $variable = $item;
            } else {
                // odd = value
                $value = $item;
                $this->{$variable} = $value;
            }
            $index++;
        }

        // calculate some properties
        if ($this->categories == '') {
            $this->categories = [];
        } else {
            $this->categories = explode(',', $this->categories);
        }
        if ($this->age == '') {
            $this->age = [];
        } else {
            $this->age = explode(',', $this->age);
        }
        if ($this->priceMax == 0 || $this->priceMax < $this->priceMin) {
            $this->priceMin = 0;
            $this->priceMax = 999;
        }
        if ($this->distance === '') {
            $this->distance = -1;
        }
        $this->priceRange = $this->priceMin . ',' . $this->priceMax;

        if (!isset($this->location)) {
            if (\Yii::$app->session->has('location_cache')) {
                $l = Json::decode(\Yii::$app->session->get('location_cache'));
                $this->latitude = $l['latitude'];
                $this->longitude = $l['longitude'];
                $this->location = $l['location'];
            } else {
                $location = IpLocation::get(IpLocation::getIp());
                $this->latitude = $location->latitude;
                $this->longitude = $location->longitude;
                $this->location = $location->city . "," . $location->country;
                \Yii::$app->session->set('location_cache', Json::encode([
                    'latitude' => $this->latitude,
                    'longitude' => $this->longitude,
                    'location' => $this->location,
                ]));
            }
        } else {
            $location = Location::getByAddress($this->location);
            $this->latitude = $location['latitude'];
            $this->longitude = $location['longitude'];
        }

        if (!isset($this->distance)) {
            $this->distance = -1;
        }

        $this->distanceIndex = $this->calculateDistanceIndex($this->distance);
    }

    public function formName()
    {
        return '';
    }

    public function search()
    {
        $query = Item::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
            'totalCount' => 1
        ]);

        $distanceQ = '( 6371  * acos( cos( radians( ' . floatval($this->latitude) . ' ) )
                    * cos( radians( `location`.`latitude` ) )
                    * cos( radians( `location`.`longitude` ) - radians(' . floatval($this->longitude) . ') )
                    + sin( radians(' . floatval($this->latitude) . ') )
                    * sin( radians( `location`.`latitude` ) ) ) )';

        $query->select($distanceQ . ' as distance, `item`.*');
        $query->orderBy('distance');
        $query->innerJoinWith(['location', 'itemHasCategories']);

//        if(isset($this->distance)){
//            $query->andWhere('distance < :meters', [':meters' => $this->convertDistanceInternal($this->distance)]);
//        }

        if(isset($this->categories)){
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

        if (isset($this->query)) {
            $query->andWhere(['LIKE', 'name', $this->query]);
        }

        return $dataProvider;
    }

    /**
     * Convert a distance (double) to a readable distance (string).
     *
     * @param $d        distance to convert
     * @return string   readable distance
     */
    public function convertDistance($d)
    {
        $d = (float)$d;
        if ($d <= 1) {
            return $d * 1000 . " m";
        } elseif ($d <= 2) {
            return ($d - 1) * 10 . " km";
        } elseif ($d < 3) {
            return ($d - 2) * 100 . " km";
        } else {
            return \Yii::t('app', 'all');
        }
    }

    /**
     * Given a distance, calculate the corresponding index.
     *
     * @param $d distance (in meters)
     * @return int index
     */
    public function calculateDistanceIndex($d) {
        if ($d >= 100000) {
            return 3;
        } else if ($d > 10000) {
            return 2 + $d / 100000;
        } else if ($d > 1000) {
            return 1 + $d / 10000;
        } else if ($d >= 0) {
            return $d / 1000;
        }
        return 3;
    }

    /**
     * Given an index, calculate the corresponding distance.
     *
     * @param $i index
     * @return int distance (in meters)
     */
    public function calculateDistance($i) {
        if ($i <= 1) {
            return $i * 1000;
        } else if ($i <= 2) {
            return ($i - 1) * 10000;
        } else if ($i < 3) {
            return ($i - 2) * 100000;
        } else {
            return -1;
        }
    }

}
