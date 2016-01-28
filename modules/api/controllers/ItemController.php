<?php
namespace api\controllers;

use api\models\Item;
use api\models\Review;
use item\models\base\CategoryHasItemFacet;
use item\models\base\ItemFacet;
use item\models\base\ItemFacetValue;
use item\models\base\ItemHasItemFacet;
use search\forms\Filter;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
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
                'only' => ['search', 'view'],
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
            'guest' => ['search', 'recommended', 'related', 'reviews', 'options', 'view'],
            'user' => ['update', 'create', 'delete', 'index', 'publish', 'unpublish', 'available-facets', 'set-facet-value']
        ];
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['view']);
        unset($actions['update']);
        return $actions;
    }

    // returns all the items from a user
    public function actionIndex($is_available = false)
    {
        $where = [ 'owner_id' => \Yii::$app->user->id];
        if($is_available){
            $where['is_available'] = 1;
        }
        return new ActiveDataProvider([
            'query' => Item::find()->where($where)
        ]);
    }

    // default action, does not need documentation
    public function actionView($id)
    {
        $where = ['id' => $id];
        $item = Item::find()->where($where)->one();
        if ($item === null) {
            throw new NotFoundHttpException('Item not found');
        }
        return $item;
    }

    public function actionUpdate($id){
        $where = ['id' => $id];
        $item = Item::find()->where($where)->one();
        if ($item === null) {
            throw new NotFoundHttpException('Item not found');
        }
        /**
         * @var $item Item
         */
        if(!$item->hasModifyRights()){
            throw new ForbiddenHttpException("Item not yours");
        }
        $d = \Yii::$app->request->getBodyParams();
        $item->setScenario('validate');
        foreach(['location_id', 'name', 'description', 'price_day', 'price_week', 'price_month', 'price_year', 'category_id'] as $i){
            if(isset($d[$i])){
                $item->{$i} = $d[$i];
            }
        }
        if($item->save(false)){
            return $item;
        }
        return $item->getErrors();
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
     * @api {post}                  items/:id/post
     * @apiName                     publishItem
     * @apiGroup                    Item
     * @apiDescription              Published an item
     *
     * @apiSuccess {Item}           item     The published item.
     * @apiParam (Number)           id                    Item id.
     * @param $id                   Item id.
     * @return ActiveDataProvider   List of all reviews.
     */
    public function actionPublish($id)
    {
        $item = Item::find()->where(['id' => $id])->one();
        if($item == null){
            throw new NotFoundHttpException("Item not found");
        }
        /**
         * @var $item Item
         */
        if(!$item->hasModifyRights()){
            throw new ForbiddenHttpException("Item not yours");
        }
        $item->setScenario("default");
        $item->validate();
        if(!$item->validate()){
            return [
                'success' => false,
                'reason' => "Item not complete yet",
                'errors' => $item->getErrors()
            ];
        }
        $item->is_available = 1;
        $item->save(false);
        return $item;
    }

    public function actionUnpublish($id)
    {
        $item = Item::find()->where(['id' => $id])->one();
        if($item == null){
            throw new NotFoundHttpException("Item not found");
        }
        /**
         * @var $item Item
         */
        if(!$item->hasModifyRights()){
            throw new ForbiddenHttpException("Item not yours");
        }
        $item->setScenario("default");
        $item->is_available = 0;
        $item->save(false);
        return $item;
    }

    /**
     * @api {get}                   items/:id/available-facets
     * @apiName                     publishItem
     * @apiGroup                    Item
     * @apiDescription              Get all the possible features + possible values
     */
    public function actionAvailableFacets($id)
    {
        $item = Item::find()->where(['id' => $id])->one();
        if($item == null){
            throw new NotFoundHttpException("Item not found");
        }

        $itemFacets = CategoryHasItemFacet::find()
            ->where(['IN', 'category_id', [$item->category_id, $item->category->parent_id]])
            ->innerJoinWith("itemFacet.itemFacetValues")
            ->asArray()
            ->all();

        return $itemFacets;
    }

    public function actionSetFacetValue($id){


        $item = Item::find()->where(['id' => $id])->one();
        if($item == null){
            throw new NotFoundHttpException("Item not found");
        }

        /**
         * @var $item Item
         */
        if(!$item->hasModifyRights()){
            throw new ForbiddenHttpException("Item not yours");
        }
        $data = \Yii::$app->request->getBodyParams();
        $facet_id = $data['facet_id'];

        $f = ItemFacet::find()->where(['id' => $facet_id])->count();
        if($f == 0){
            throw new ForbiddenHttpException("Item facet is not found");
        }

        if(isset($data['value_id'])){
            $f = ItemFacetValue::find()->where(['id' => $data['value_id']])->count();
            if($f == 0){
                throw new ForbiddenHttpException("Item facet value is not found");
            }
            $itemHIF = ItemHasItemFacet::find()->where(['item_id' => $id, 'item_facet_id' => $facet_id])->one();
            if($itemHIF == null){
                $itemHIF = new ItemHasItemFacet();
                $itemHIF->item_id = $item->id;
                $itemHIF->item_facet_id = $facet_id;
            }
            $itemHIF->item_facet_value_id = $data['value_id'];
            $itemHIF->save();
            return 1;
        }else if(isset($data['value'])){
            $itemHIF = ItemHasItemFacet::find()->where(['item_id' => $id, 'item_facet_id' => $facet_id])->one();
            if($data['value'] == 1){
                if($itemHIF !== null){
                    return 1;
                }
                $itemHIF = new ItemHasItemFacet();
                $itemHIF->item_id = $item->id;
                $itemHIF->item_facet_id = $facet_id;
                $itemHIF->save();
                return 1;
            }else{
                $itemHIF->delete();
                return 1;
            }
        }else{
            throw new ForbiddenHttpException("Either a value (boolean) or a value_id should be defined");
        }
    }

}