<?php
namespace app\modules\search2\models;

use app\modules\item\models\IpLocation;
use app\modules\item\models\Item;
use app\modules\item\models\Location;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\Json;

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
    public $priceMin = null;
    public $priceMax = null;
    public $page = 0;

    /**
     * Find items.
     *
     * @return ActiveDataProvider the results
     */
    public function findItems()
    {
        // initialize the query
        $query = $this->initQuery();

        // apply filters
        $this->filterLocation($query, $this->location, 25 * 1000);
        $this->filterSearchTerm($query, $this->query);
        $this->filterCategories($query, $this->categories);
        $this->filterPrice($query, $this->priceMin, $this->priceMax);
        $this->filterIsAvailable($query);
        $this->pageResults($query);

        // give back the data provider
        return $this->getDataProvider($query);
    }

    /**
     * Initialize the query.
     *
     * @return ActiveQuery query object
     */
    public function initQuery()
    {
        $query = Item::find();
        $query->select('`item`.*');

        return $query;
    }

    /**
     * Get a data provider.
     *
     * @param ActiveQuery $query query object
     * @return ActiveDataProvider data provider
     */
    public function getDataProvider($query)
    {
        return $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);
    }

    /**
     * Get a data provider.
     *
     * @param ActiveQuery $query query object
     */
    public function pageResults($query){
        $query->limit(10)->offset(round($this->page) * 10);
    }

    /**
     * Filter on a search term.
     *
     * @param ActiveQuery $query
     * @param $searchTerm
     */
    public function filterSearchTerm($query, $searchTerm = null)
    {
        if ($searchTerm !== null) {
            $query->andWhere(['LIKE', 'name', $searchTerm]);
        }
    }

    /**
     * Filter on a search term.
     *
     * @param ActiveQuery $query
     */
    public function filterIsAvailable($query)
    {
        $query->andWhere(['is_available' => 1]);
    }

    /**
     * Filter the results by location.
     *
     * @param ActiveQuery $query the query object to apply the filter on
     * @param array $location location: array with longitude and latitude properties
     * @param int $distance the distance (in meters)
     */
    public function filterLocation($query, $location = null, $distance = null)
    {
        if ($distance !== null && $location !== null) {
            $geodata = $this->_getGeoData($location);
            if ($geodata['success']) {
                $latitude = $geodata['latitude'];
                $longitude = $geodata['longitude'];
                $distanceQ = '( 6371  * acos( cos( radians( ' . floatval($latitude) . ' ) )
                        * cos( radians( `location`.`latitude` ) )
                        * cos( radians( `location`.`longitude` ) - radians(' . floatval($longitude) . ') )
                        + sin( radians(' . floatval($latitude) . ') )
                        * sin( radians( `location`.`latitude` ) ) ) )';

                $query->select($distanceQ . ' as distance, `item`.*');
                $query->innerJoinWith('location');
                $query->andWhere($distanceQ . ' < :meters', [':meters' => $distance]);
                $query->orderBy('distance');
            }
        }
    }

    /**
     * Filter the results by categories.
     *
     * @param ActiveQuery $query the query object to apply the filter on
     * @param array $categories the categories (ids) to filter on
     */
    public function filterCategories($query, $categories = [])
    {
        if (isset($categories) && $categories !== null) {
            $query->orWhere(['IN', 'category_id', $categories]);
        }
    }

    /**
     * Filter the results by price.
     *
     * @param ActiveQuery $query the query object to apply the filter on
     * @param int $priceMin the minimum price to search for
     * @param int $priceMax the maximum price to search for
     */
    public function filterPrice($query, $priceMin = null, $priceMax = null)
    {
        if (isset($priceMin) && $priceMin !== null) {
            $query->andWhere('price_week >= :low', [
                ':low' => $priceMin,
            ]);
        }
        if (isset($priceMax) && $priceMax !== null) {
            $query->andWhere('price_week <= :high', [
                ':high' => $priceMax,
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
    private function _getGeoData($location = null)
    {
        if (isset($location) && $location !== null) {
            if (\Yii::$app->session->has('location_cache')) {
                $l = Json::decode(\Yii::$app->session->get('location_cache'));
                $latitude = $l['latitude'];
                $longitude = $l['longitude'];
            } else {
                $location = IpLocation::get(IpLocation::getIp());
                $latitude = $location->latitude;
                $longitude = $location->longitude;
                $location = $location->city . "," . $location->country;
                \Yii::$app->session->set('location_cache', Json::encode([
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'location' => $location,
                ]));
            }
        } else {
            $location = Location::getByAddress($location);
            $latitude = $location['latitude'];
            $longitude = $location['longitude'];
        }

        return [
            'longitude' => $longitude,
            'latitude' => $latitude,
            'success' => (strlen($longitude) > 0 && strlen($latitude) > 0 && $location !== null)
        ];
    }

    /**
     * Load the parameters.
     *
     * @param array $params array parameters (result from parse query string)
     */
    public function loadParameters($params)
    {
        if (isset($params['query'])) {
            $this->query = $params['query'];
        }
        if (isset($params['location'])) {
            $this->location = $params['location'];
        }
        if (isset($params['priceMin'])) {
            $this->priceMin = $params['priceMin'];
        }
        if (isset($params['priceMax'])) {
            $this->priceMax = $params['priceMax'];
        }
        if (isset($params['categories'])) {
            $this->categories = $params['categories'];
        }
    }

    /**
     * Sets the page number of the requested results
     * @param int $page
     */
    public function setPage($page){
        $this->page = $page;
    }

    /**
     * Parse the query string.
     *
     * @param string $query query string
     * @return array query parameters
     */
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