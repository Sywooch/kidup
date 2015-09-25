<?php

namespace app\modules\search\forms;

use app\components\Cache;
use app\models\base\CategoryHasFeature;
use app\models\base\Feature;
use app\models\base\ItemSearch;
use app\modules\item\models\Category;
use app\modules\item\models\Item;
use app\modules\item\models\Location;
use app\modules\search\models\IpLocation;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\Html;

class Filter extends Model
{
    public $query = null;
    private $_query;
    public $categories;
    public $location;
    public $longitude;
    public $latitude;
    public $categoryId;
    public $priceMin = 0;
    public $priceMax = 499;
    public $priceUnit = 'week';
    public $featureFilters;
    public $features;
    public $singularFeatures;
    public $page;

    public $resultsAreFake = false; // if the results are not results from the actual query, but just to have something
    public $resultText = false; // toptext in resultPage
    public $estimatedResultCount = 0; // number of estimated search results

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

    /**
     * Find items.
     *
     * @return ActiveDataProvider the results
     */
    public function findItems()
    {

        $this->queryExtraction();
        $this->findFeatureFilters();
        // initialize the query
        $this->_query = Item::find();

        // apply filters
        $this->filterCategories();
        $this->filterPrice();
        $this->filterFeatures();
        $this->_query->andWhere(['is_available' => 1]);
        $this->_query->limit(12)->offset(round($this->page) * 12); // pagination

        $countQuery = clone $this->_query;

        // show something if there is no result
        if ($countQuery->count() == 0) {

            $this->resultText = \Yii::t('search', 'No results found for your query.');

            $this->_query = Item::find();
            $this->resultsAreFake = true;

            // ditch all filters except location and topcategory
            $this->filterLocation();
            $this->filterCategories();
            $this->_query->andWhere(['is_available' => 1]);
            $this->_query->limit(12)->offset(round($this->page) * 12); // pagination
        }

        $this->filterLocation();

        // search results, order by some semi random but constant order
        $res = $this->_query->orderBy('(item.id mod 10)*item.created_at')->all();

        // estimate the total number of results
        $this->_query = Item::find();

        $this->filterCategories();
        $this->filterPrice();
        $this->_query->andWhere(['is_available' => 1]);

        $this->estimatedResultCount = $this->_query->count();

        return $res;
    }

    /**
     * Finds categories and features in the text query, places them in object
     * @return mixed
     */
    private function queryExtraction()
    {
        // item id finding query

        $input = explode(" ", $this->query);
        $query = '(';
        $query2 = '("';
        foreach ($input as $i) {
            $query .= $i . "* ";
            $query2 .= $i . " ";
        }
        $query .= ")";
        $query2 .= '")';

        $res = ItemSearch::find()
            ->select("text, component_id, component_type, MATCH(text) AGAINST(:q IN BOOLEAN MODE) as score")
            ->distinct(true)
            ->params([':q' => $query . ' ' . $query2])
            ->orderBy('score DESC')
            ->having('score > 0')
            ->where(['IN', 'component_type', ['sub-cat', 'main-cat']])
            ->limit(5)
            ->asArray()
            ->all();
        if (isset($res[0])) {
            $this->categories = [$res[0]['component_id']];
        }
        if (isset($res[0]) && isset($res[1])) {
            if ($res[1]['score'] < $res[0]['score']) {
                $this->categories = [$res[0]['component_id']];
            } else {
                $this->categories = [];
                foreach ($res as $r) {
                    $this->categories[] = $r['component_id'];
                }
            }
        }
    }

    private function findFeatureFilters()
    {
        $features = Feature::find()->where([
            'IN',
            'name',
            ['Condition', 'Exchange', 'Smoke Free', 'Pet Free']
        ])->indexBy('id')->all();
        if (is_array($this->categories) && count($this->categories) == 1) {
            $chts = CategoryHasFeature::findAll([
                'category_id' => $this->categories[0]
            ]);
            foreach ($chts as $cht) {
                $feature[$cht->feature->id] = $cht->feature;
            }
        }
        $this->featureFilters = $features;
    }

    public function loadQueriedFeatures($array)
    {
        if (isset($array['features'])) {
            $this->features = $array['features'];
        }
        if (isset($array['singularFeatures'])) {
            $this->singularFeatures = $array['singularFeatures'];
        }
    }

    public function filterFeatures()
    {
        if (!is_null($this->singularFeatures)) {
            $singleFeatureIds = [];
            foreach ($this->singularFeatures as $id => $val) {
                if ($val == 0) {
                    continue;
                }
                $singleFeatureIds[] = $id;
            }
            if (count($singleFeatureIds) > 0) {
                $this->_query->innerJoinWith([
                    'itemHasFeatureSingulars' => function ($query) use ($singleFeatureIds) {
                        $query->where(['IN', 'item_has_feature_singular.feature_id', $singleFeatureIds]);
                    }
                ]);
            }
        }


        if (!is_null($this->features)) {
            $this->_query->innerJoinWith([
                'itemHasFeatures' => function ($query) {
                    foreach ($this->features as $featureId => $val) {
                        foreach ($val as $valId => $bool) {
                            if ($bool == 0) {
                                continue;
                            }
                            $query->orWhere([
                                'item_has_feature.feature_id' => $featureId,
                                'item_has_feature.feature_value_id' => $valId
                            ]);
                        }
                    }
                }
            ]);
        }
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
    public function filterCategories()
    {
        if (is_array($this->categories)) {
            if ($this->resultsAreFake) {
                // if the results are faked, use the parent category of the selected search category
                foreach ($this->categories as $cat) {
                    $cat = Category::find()->where(['id' => $cat])->andWhere('parent_id is not null')->one();
                    if ($cat !== null) {
                        $this->categories = [$cat->parent_id]; // set parent as top category
                    }
                }
            }
            foreach ($this->categories as $cat) {
                $cat = Category::find()->where(['id' => $cat])->one();

                if ($cat !== null) {
                    if($this->resultText == false){
                        $this->resultText = \Yii::t('search', 'Results for').' '.\Yii::t("categories_and_features", $cat->name);
                    }
                    // add all subcategories if searching on a parent category

                    if($cat->parent_id === null){
                        foreach ($cat->children as $child) {
                            $this->categories[] = $child->id;
                        }
                    }
                }
            }
            $this->_query->where(['IN', 'category_id', $this->categories]);
        }else{
            $suggestionWord = ItemSearch::find()->orderBy('rand()')->where(['language_id' => \Yii::$app->language])->one();
            $t = \Yii::t('categories_and_features', $suggestionWord->text);
            if($this->query == ' ' || $this->query == '%20'){
                $this->resultText = \Yii::t('search', "Your search was empty: here are some suggestions from other KidUp users!");
            }else{
                $this->resultText = \Yii::t('search', "We couldn't find {0}. Perhaps try {1}?",[
                    '<b>'.$this->query.'</b>',
                    Html::a($t, '@web/search/'.$t, ['data-pjax' => 0])
                ]);
            }
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
        if (in_array($this->priceUnit, ['price_day', 'price_week', 'price_month'])) {
            $field = $this->priceUnit;
            if (isset($this->priceMin) && $this->priceMin !== null) {

                $this->_query->andWhere(':field >= :low', [
                    ':field' => $field,
                    ':low' => $this->priceMin,
                ]);
            }
            if (isset($this->priceMax) && $this->priceMax !== null) {
                $this->_query->andWhere(':field <= :high', [
                    ':field' => $field,
                    ':high' => $this->priceMax,
                ]);
            }
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
        if ($location == null) {
            return false;
        }
        $this->latitude = $location['latitude'];
        $this->longitude = $location['longitude'];

        return (strlen($this->longitude) > 0 && strlen($this->latitude) > 0 && $location !== null);
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
                        $this->longitude = $location->longitude;
                        $this->latitude = $location->latitude;
                        if (strlen($location->city) > 0 && strlen($location->country_name) > 0) {
                            $location = $location->city . ", " . $location->country_name;
                        } else {
                            if (strlen($location->country_name) > 0) {
                                $location = $location->country_name;
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