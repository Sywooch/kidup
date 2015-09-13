<?php

namespace app\modules\search\forms;

use app\components\Cache;
use app\modules\item\models\Item;
use app\modules\item\models\Location;
use app\modules\search\models\IpLocation;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class Filter extends Model
{
    public $query = null;
    private $_query;
    public $location;
    public $longitude;
    public $latitude;
    public $categoryId = null;
    public $priceMin = 0;
    public $priceMax = 499;
    public $priceUnit = 'week';
    public $page;
    public $activeFilters;

    public function formName()
    {
        return 'search-filter';
    }

    public function rules()
    {
        return [
            [['query', 'location', 'priceUnit'], 'string'],
            [['longitude', 'latitude'], 'double'],
            [['categoryId', 'page'], 'integer'],
            [['priceMin', 'priceMax'], 'integer']
        ];
    }

    public function processActiveFilters(){

    }

    /**
     * Find items.
     *
     * @return ActiveDataProvider the results
     */
    public function findItems()
    {
        $this->processActiveFilters();
        // initialize the query
        $this->_query = Item::find();

        // apply filters
        $this->filterLocation();
        $this->filterCategory();
        $this->filterPrice();
        $this->_query->andWhere(['is_available' => 1]);
        $this->_query->limit(12)->offset(round($this->page) * 12);

        // give back the results
        return $this->getResults($this->_query);
    }

    /**
     * Get the results.
     *
     * @param ActiveQuery $query query object
     * @return Object results
     */
    public function getResults($query)
    {
        // randomize real quickly, only temporarily
        $query->orderBy('rand()');
        return $query->all();
    }

    /**
     * Get a data provider.
     *
     * @param ActiveQuery $query query object
     */
    public function pageResults($query)
    {
        $query->limit(12)->offset(round($this->page) * 12);
    }

    /**
     * Filter on whether an item is available.
     *
     * @param ActiveQuery $query
     */
    public function filterIsAvailable($query)
    {

    }

    /**
     * Filter the results by location.
     *
     * @param ActiveQuery $query the query object to apply the filter on
     * @param array $location location: array with longitude and latitude properties
     * @param int $distance the distance (in meters)
     */
    public function filterLocation()
    {
        if (is_null($this->latitude) || is_null($this->location)) {
             $this->_getGeoData($this->location);
        }

        if (!is_null($this->latitude) && !is_null($this->location)) {
            $latitude = floatval($this->latitude);
            $longitude = floatval($this->longitude);
            $distanceQ = '( 6371  * acos( cos( radians( ' . ($latitude) . ' ) )
                        * cos( radians( `location`.`latitude` ) )
                        * cos( radians( `location`.`longitude` ) - radians(' . ($longitude) . ') )
                        + sin( radians(' . ($latitude) . ') )
                        * sin( radians( `location`.`latitude` ) ) ) )';
            $this->_query->select($distanceQ . ' as distance, `item`.*');
            $this->_query->innerJoinWith('location');

            $this->_query->orderBy('distance');
        } else {
            // no matching location could be found, return no results
            //$query->andWhere('true = false');
        }
    }

    /**
     * Filter the results by category.
     *
     * @param ActiveQuery $query the query object to apply the filter on
     * @param array $categories the categories (ids) to filter on
     */
    public function filterCategory()
    {
        if (isset($this->category_id) && $this->category_id !== null) {
            $this->_query->where(['category_id' => $this->category_id]);
        }
    }

    /**
     * Filter the results by price.
     *
     * @param ActiveQuery $query the query object to apply the filter on
     * @param int $priceMin the minimum price to search for
     * @param int $priceMax the maximum price to search for
     * @param String $priceUnit either "day", "week" or "month"
     */
    public function filterPrice()
    {
        $field = "price_week";
        if (in_array($this->priceUnit, ['day', 'week', 'month'])) {
            $field = 'price_' . $this->priceUnit;
        }

        if (isset($this->priceMin) && $this->priceMin !== null) {
            $this->_query->andWhere($field . ' >= :low', [
                ':low' => $this->priceMin,
            ]);
        }
        if (isset($this->priceMax) && $this->priceMax !== null) {
            $this->_query->andWhere($field . ' <= :high', [
                ':high' => $this->priceMax,
            ]);
        }
    }

    /**
     * Get geographical data for a given location.
     *
     * @param null $location the location to retrieve the geographical data for
     * @return array with keys:
     *              longitude   the found longitude for location
     *              latitude    the found latitude for location
     *              success     whether the geographical data was found
     */
    private function _getGeoData()
    {
        $location = $this->location;
        $location = Cache::data('location_' . $this->location, function () use ($location) {
            return Location::getByAddress($location);
        }, 30 * 24 * 60 * 60);
        if($location == null){
            return false;
        }
        $this->latitude = $location['latitude'];
        $this->longitude = $location['longitude'];

        return  (strlen($this->longitude) > 0 && strlen($this->latitude) > 0 && $location !== null);
    }

    public function setLocation()
    {
        if (isset($this->location)) {
            return false;
        }
        // use IP based location
        $ip = Yii::$app->request->getUserIP();
        $location = IpLocation::get($ip);
        if ($ip !== null) {
            if (strlen($location['city']) > 0 && strlen($location['country']) > 0) {
                $location = $location['city'] . ", " . $location['country'];
            } else {
                if (strlen($location['country']) > 0) {
                    $location = $location['country'];
                } else {
                    // use a fallback method
                    $location = Location::getByIP($ip);
                    if ($location !== null) {
                        $this->longitude = $location['longitude'];
                        $this->latitude = $location['latitude'];
                        if (strlen($location['city']) > 0 && strlen($location['country_name']) > 0) {
                            $location = $location['city'] . ", " . $location['country_name'];
                        } else {
                            if (strlen($location['country_name']) > 0) {
                                $location = $location['country_name'];
                            } else {
                                // if it is really not traceable
                                $location = null;
                            }
                        }
                    } else {
                        $location = null;
                    }
                }
            }
            $this->location = $location;
        }
        return true;
    }
}