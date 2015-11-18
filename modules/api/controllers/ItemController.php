<?php
namespace api\controllers;

use api\models\Item;
use item\controllers\ViewController;
use api\models\Review;
use search\forms\Filter;
use yii\data\ActiveDataProvider;
use yii\web\HttpException;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;

class ItemController extends Controller
{
    public function init()
    {
        $this->modelClass = Item::className();
        parent::init();
    }

    public function accessControl()
    {
        return [
            'guest' => ['index', 'view', 'search', 'recommended', 'related', 'reviews', 'options'],
            'user' => ['update', 'create']
        ];
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete']);
//        unset($actions['create']);
        unset($actions['index']);
//        unset($actions['update']);
        unset($actions['view']);
        return $actions;
    }

    // default action, does not need documentation
    public function actionIndex()
    {
        return new ActiveDataProvider([
            'query' => Item::find()->where(['is_available' => 1])
        ]);
    }

    // default action, does not need documentation
    public function actionView($id)
    {
        $item = Item::find()->where(['is_available' => 1, 'id' => $id])->one();
        if ($item === null) {
            throw new NotFoundHttpException('Item not found');
        }
        return $item;
    }

    /**
     * @api {get} items/related
     * @apiName         relatedItem
     * @apiGroup        Item
     * @apiDescription  Find items which are related to a given item.
     *
     * @apiParam {Number}       item_id           The item_id of the item to find related items for.
     * @apiSuccess {Object[]}   related_items     A list of related items.
     */
    public function actionRelated()
    {
        $params = \Yii::$app->request->get();
        if (!array_key_exists('item_id', $params)) {
            throw new NotAcceptableHttpException('No item_id is given.');
        }
        $itemId = $params['item_id'];
        /**
         * @var Item $item
         */
        $item = Item::find()->where(['is_available' => 1, 'id' => $itemId])->one();
        if ($item === null) {
            throw new NotFoundHttpException('Item not found');
        }
        $related_items = $item->getRecommendedItems($item, 2);
        return [
            'related_items' => $related_items
        ];
    }

    /**
     * @api {get}       items/recommended
     * @apiName         recommendedItem
     * @apiGroup        Item
     * @apiDescription  Get recommended items.
     *
     * @apiSuccess {Object[]}   recommended_items     A list of recommended items.
     */
    public function actionRecommended()
    {
        // todo this is rather ugly, but an activedataprovider needs to be returned for consitency
        $items = Item::getRecommended(4);


        $res = [];
        foreach ($items as $item) {
            $res[] = $item->id;
        }
        return new ActiveDataProvider([
            'query' => Item::find()->where(['IN', 'id', $res])
        ]);
    }

    /**
     * @api {post} items/search
     * @apiName         searchItem
     * @apiGroup        Item
     * @apiDescription  Search for items.
     *
     * @apiParam {Number}       page                        The page to load (optional, default: 0).
     *
     * @apiParam {Object[]}     price                       The specification of the price filter (optional).
     * @apiParam {String}       price.price_unit            The price unit, which is one of the following:
     *                                                          "price_day"    Price per day
     *                                                          "price_week"   Price per week
     *                                                          "price_month"  Price per month
     * @apiParam {Number}       price.price_min             The minimum price to search for.
     * @apiParam {Number}       price.price_max             The maximum price to search for.
     *
     * @apiParam {Object[]}     location_by_name            The specification of the location by name filter (optional).
     * @apiParam {String}       location_by_name.location   The name of the location (e.g. a city or a place).
     *
     * @apiParam {Object[]}     location_by_geo             The specification of the geo-location filter (optional).
     * @apiParam {Number}       location_by_geo.longitude   The longitude of the location.
     * @apiParam {Number}       location_by_geo.latitude    The latitude of the location.
     *
     * @apiParam {Number[]}     category                    A list of all categories (specified by their category_id) that are enabled (optional).
     *
     * @apiParam {Object[]}     feature                     The specification of the feature filter (optional).
     * @apiParam {Number}       feature[].name              The feature_id of the feature that is used.
     * @apiParam {Number[]}     feature[].value[]           A list of feature_value_id which are used.
     *
     * @apiSuccess {Number}     num_pages                   The total number of pages.
     * @apiSuccess {Number}     num_items                   The total number of items.
     * @apiSuccess {Object[]}   results                     A list of items found by the search system.
     */
    public function actionSearch()
    {
        // load the page number
        $page = \Yii::$app->request->post('page', 0);

        // load the other parameters
        $params = \Yii::$app->request->post();

        // set some read-only parameters
        $pageSize = 12;

        $model = new Filter();
        $model->_query = Item::find();
        $model->page = $page;
        $model->pageSize = $pageSize;

        // load the pricing
        if (isset($params['price_unit'])) {
            $model->priceUnit = $params['price_unit'];
            if (isset($params['price_min'])) {
                $model->priceMin = $params['price_min'];
            }
            if (isset($params['price_max'])) {
                $model->priceMax = $params['price_max'];
            }
        }

        // load location
        if (isset($params['location_by_name'])) {
            if (isset($params['location_by_name']['location'])) {
                $model->location = $params['location_by_name']['location'];
            }
        }

        // load location based on longitude and latitude
        if (isset($params['location_by_geo'])) {
            $locationByGeo = $params['location_by_geo'];
            if (isset($locationByGeo['longitude']) && isset($locationByGeo['latitude'])) {
                $model->longitude = $locationByGeo['longitude'];
                $model->latitude = $locationByGeo['latitude'];
            }
        }

        // load the categories
        if (isset($params['category'])) {
            $model->categories = $params['category'];
        }

        // now get the query
        $query = $model->getQuery(true);

        // and give back the results
        return [
            'items' => $model->findItems(),
            'filters' => $model->featureFilters,
            // todo make this real
            '_meta' => [
                'currentPage' => 0,
                'pageCount' => 1,
                'perPage' => 20,
                'totalCount' => 100
            ]
        ];
    }

    public function actionReviews($id)
    {
        $this->modelClass = Review::className();
        return new ActiveDataProvider([
            'query' => Review::find()->where(['item_id' => $id, 'type' => Review::TYPE_USER_PUBLIC])
        ]);
    }

}