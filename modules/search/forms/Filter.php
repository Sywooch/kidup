<?php

namespace search\forms;

use app\components\Cache;
use item\models\base\CategoryHasFeature;
use item\models\base\Feature;
use item\models\Category;
use item\models\Item;
use item\models\Location;
use search\models\base\ItemSearch;
use search\models\IpLocation;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\Html;

class Filter extends Model
{
    public $query = null;
    /**
     * @var ActiveQuery $_query
     */
    public $_query = false;
    public $categories;
    public $location;
    public $longitude;
    public $latitude;
    public $categoryId;
    public $priceMin = 0;
    public $priceMax = 499;
    public $priceUnit = 'week';
    public $page;
    public $pageSize = 12;

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
     * Get the query based on the values of the filters.
     *
     * @return \yii\db\Query Query object
     */
    public function getQuery($isAPICall = false) {
        $this->queryExtraction();
        // initialize the query
        if($this->_query === false){
            $this->_query = Item::find();
        }

        // apply filters
        $this->filterCategories();
        $this->filterPrice();
        $this->_query->andWhere(['is_available' => 1]);

        // count before limiting the query
        $countQuery = clone $this->_query;
        $countQuery = (int)$countQuery->count();
        $this->estimatedResultCount = $countQuery;

        // set the pagination
        $this->_query->limit($this->pageSize)->offset(round($this->page) * $this->pageSize);

        // show something if there is no result
        if (!$isAPICall && $countQuery == 0) {
            $this->resultText = \Yii::t('search.no_results', 'No results found for your query.');
            $this->resultsAreFake = true;
            $this->_query = Item::find();
            $this->_query->andWhere(['is_available' => 1]);
            $this->_query->limit($this->pageSize)->offset(round($this->page) * $this->pageSize); // pagination
            $countQuery = clone $this->_query;
            $countQuery = (int)$countQuery->count();
            $this->estimatedResultCount = $countQuery;
        }

        if ($isAPICall) {
            $this->filterLocationLongitudeLatitude();
        } else {
            $this->filterLocation();
        }

        // search results, order by some semi random but constant order
        return $this->_query->orderBy('(item.id mod 8)*item.created_at');
    }

    /**
     * Find items.
     *
     * @return array the results
     */
    public function findItems()
    {
        return $this->getQuery()->all();
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

    /**
     * Filter the results by location.
     */
    public function filterLocation()
    {
        if (is_null($this->latitude) || is_null($this->location)) {
            $this->_getGeoData();
        }

        if (!is_null($this->latitude) && !is_null($this->location)) {
            $this->filterLocationLongitudeLatitude();
        } else {
            // no matching location could be found, return no results
            //$query->andWhere('true = false');
        }
    }

    /**
     * Filter the location based on longitude and latitude.
     */
    public function filterLocationLongitudeLatitude() {
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
    }

    /**
     * Filter the results by category.
     */
    public function filterCategories()
    {
        if (is_array($this->categories)) {
            if ($this->resultsAreFake) {
                // if the results are faked, use the parent category of the selected search category
                $this->categories = null;
            }
            foreach ($this->categories as $cat) {
                $cat = Category::find()->where(['id' => $cat])->one();

                if ($cat !== null) {
                    if ($this->resultText == false) {
                        $this->resultText = \Yii::t('search.results_for_category', 'Results for {category}', [
                            'category' => $cat->getTranslatedName()
                        ]);
                    }
                    // add all subcategories if searching on a parent category

                    if ($cat->parent_id === null) {
                        foreach ($cat->children as $child) {
                            $this->categories[] = $child->id;
                        }
                    }
                }
            }
            $this->_query->where(['IN', 'category_id', $this->categories]);
        } else {
            $this->resultsAreFake = true;
            $suggestionWord = ItemSearch::find()->orderBy('rand()')->where(['language_id' => \Yii::$app->language])->one();
            if ($suggestionWord !== null) {
                $t = $suggestionWord->text;
            } else {
                $t = '';
            }
            $this->resultText = \Yii::t('search.nothing_found_suggestions', "We couldn't find {0}. Perhaps try {1}?", [
                '<b>"' . $this->query . '"</b>',
                Html::a($t, '@web/search/' . $t, ['data-pjax' => 0])
            ]);
        }
    }

    /**
     * Filter the results by price.
     */
    public function filterPrice()
    {
        if (in_array($this->priceUnit, ['price_day', 'price_week', 'price_month'])) {
            if (isset($this->priceMin) && $this->priceMin !== null) {
                $this->_query->andWhere(['>', $this->priceUnit, $this->priceMin]);
            }
            if (isset($this->priceMax) && $this->priceMax !== null) {
                $this->_query->andWhere(['<', $this->priceUnit, $this->priceMax]);
            }
        }
    }

    /**
     * Get geographical data for a given location.
     * @return array with keys:
     *              longitude   the found longitude for location
     *              latitude    the found latitude for location
     *              success     whether the geographical data was found
     */
    private function _getGeoData()
    {
        $location = $this->location;
        $location = Cache::build('location')->variations($location)->duration(30 * 24 * 60 * 60)
            ->data(function () use ($location) {
                return Location::getByAddress($location);
            });
        if ($location == null) {
            return false;
        }
        $this->latitude = $location['latitude'];
        $this->longitude = $location['longitude'];

        return (strlen($this->longitude) > 0 && strlen($this->latitude) > 0 && $location !== null);
    }

    public function setLocation()
    {
        if (isset($this->location) || (isset($this->longitude) && isset($this->latitude))) {
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
                    if ($location !== null && $location !== false) {
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