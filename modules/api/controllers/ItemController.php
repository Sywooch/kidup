<?php
namespace api\controllers;

use api\models\Item;
use api\models\Review;
use search\forms\Filter;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;

class ItemController extends Controller
{

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'cache' => [
                'class' => 'yii\filters\PageCache',
                'only' => ['search'],
                'duration' => 60 * 20,
                'enabled' => YII_CACHE,
                'variations' => [
                    \Yii::$app->language,
                    \Yii::$app->request->get()
                ],
            ],
        ]);
    }

    public function init()
    {
        $this->modelClass = Item::className();
        parent::init();
    }

    public function accessControl()
    {
        return [
            'guest' => ['index', 'search', 'recommended', 'related', 'reviews', 'options', 'view'],
            'user' => ['update', 'create', 'delete']
        ];
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete']);
        unset($actions['create']);
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
     * @api {get} items/search
     * @apiName         searchItem
     * @apiGroup        Item
     * @apiDescription  Search for items.
     *
     * @apiParam {Number}       page                        The page to load (optional, default: 0).
     *
     * @apiParam {String}       price_unit            The price unit, which is one of the following:
     *                                                          "price_day"    Price per day
     *                                                          "price_week"   Price per week
     *                                                          "price_month"  Price per month
     * @apiParam {Number}       price_min             The minimum price to search for.
     * @apiParam {Number}       price_max             The maximum price to search for.
     *
     * @apiParam {String}       location_name   The name of the location (e.g. a city or a place).
     *
     * @apiParam {Number}       longitude   The longitude of the location.
     * @apiParam {Number}       latitude    The latitude of the location.
     *
     * @apiParam {Number[]}     category                    A list of all comma seperated category ids that are enabled (optional).
     *
     * @apiParam {Object[]}     feature                     The specification of the feature filter (optional).
     * @apiParam {Number}       feature[].name              The feature_id of the feature that is used.
     * @apiParam {Number[]}     feature[].value[]           A list of feature_value_id which are used.
     *
     * @apiSuccess {Number}     num_pages                   The total number of pages.
     * @apiSuccess {Number}     num_items                   The total number of items.
     * @apiSuccess {Object[]}   results                     A list of items found by the search system.
     * @param int $page
     * @param int $price_min
     * @param int $price_max
     * @param string $price_unit
     * @param bool $location_name
     * @param bool $longitude
     * @param bool $latitude
     * @param bool $category
     * @return ActiveDataProvider
     */
    public function actionSearch(
        $page = 0,
        $price_min = 0,
        $price_max = 9999,
        $price_unit = 'price_week',
        $location_name = false,
        $longitude = false,
        $latitude = false,
        $category = false
    ) {
        // set some read-only parameters
        $pageSize = 12;

        $model = new Filter();
        $model->_query = Item::find();
        $model->page = $page;
        $model->pageSize = $pageSize;

        $model->priceUnit = $price_unit;
        $model->priceMax = (int)$price_max;
        $model->priceMin = (int)$price_min;

        // load location
        if ($location_name) {
            $model->location = $location_name;
        } elseif (isset($longitude) && isset($latitude)) {
            $model->longitude = $longitude;
            $model->latitude = $latitude;
        }

        // load the categories
        if ($category) {
            $model->categories = explode(",", $category);
        }

        return new ActiveDataProvider([
            'query' => $model->getQuery(true)
        ]);
    }

    /**
     * @api {get}                   items/reviews/:id
     * @apiName                     reviewsItem
     * @apiGroup                    Item
     * @apiDescription              Get reviews belonging to an item.
     *
     * @apiSuccess {Object[]}       recommended_items     A list of recommended items.
     * @apiParam (Number)           id                    Item id.
     * @param $id                   Item id.
     * @return ActiveDataProvider   List of all reviews.
     */
    public function actionReviews($id)
    {
        $this->modelClass = Review::className();
        return new ActiveDataProvider([
            'query' => Review::find()->where(['item_id' => $id, 'type' => Review::TYPE_USER_PUBLIC])
        ]);
    }

    /**
     * @api (post)                      items/create
     * @apiName                         createItem
     * @apiGroup                        Item
     * @apiDescription                  Create an item record.
     *
     * @apiParam (String)  name         The name of the item.
     * @apiParam (String)  description  The description of the item.
     * @apiParam (Number)  price_week   The weekly price for the item.
     * @apiParam (Number)  min_renting_days
     *                                  The minimal number of days this item can be rented.
     * @apiParam (Number)  category_id  The identifier of the category for the item.
     *
     * @apiSuccess (Object[])           Array containing a key item_id which value is the identifier of the newly
     *                                  created item. It also contains a key errors which contains a list of all found
     *                                  errors during creation.
     */
    public function actionCreate() {
        // Parameter checking
        $required_params = ['name', 'description', 'price_week', 'min_renting_days'];
        $params = \Yii::$app->request->post();
        foreach ($required_params as $required_param) {
            if (!array_key_exists($required_param, $params)) {
                throw new NotAcceptableHttpException('No ' . $required_param . ' is given.');
            }
        }

        // Item creation (validation is done in the item model)
        $item = new Item();
        $item->name = \Yii::$app->request->post('name');
        $item->description = \Yii::$app->request->post('description');
        $item->price_week = \Yii::$app->request->post('price_week');
        $item->min_renting_days = \Yii::$app->request->post('min_renting_days');
        $item->category_id = \Yii::$app->request->post('category_id');
        $item->owner_id = \Yii::$app->user->id;
        $item->save();

        // Give back result
        $item_id = $item->id;
        return [
            'item_id' => $item_id,
            'errors' => $item->getErrors()
        ];
    }

    /**
     * @api (delete)                    items/:id
     * @apiName                         deleteItem
     * @apiGroup                        Item
     * @apiDescription                  Delete an item record.
     *
     * @apiParam (Number)  id           The identifier of the item to delete.
     *
     * @apiSuccess (Object[])           Array containing a key success which is true when the record was deleted
     *                                  successfully, false otherwise.
     * @param $id
     * @return int
     */
    public function actionDelete($id) {
        $items = Item::find()->where(['id' => $id]);
        return [
            'success' => ($items->count() > 0 ? $items->one()->delete() : false)
        ];
    }

}