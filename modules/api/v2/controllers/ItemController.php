<?php
namespace api\v2\controllers;

use api\v2\models\Item;
use api\v2\models\Review;
use app\components\filters\OwnModelAccessFilter;
use item\models\categoryHasItemFacet\CategoryHasItemFacet;
use item\models\itemFacet\ItemFacet;
use item\models\itemFacetValue\ItemFacetValue;
use item\models\itemHasItemFacet\ItemHasItemFacet;
use search\components\ItemSearchDb;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
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
            'modelAccess' => [
                'class' => OwnModelAccessFilter::className(),
                'only' => ['update', 'delete'],
                'modelClass' => Item::className()
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
            'guest' => ['reviews', 'options', 'view', 'search-suggestions', 'index'],
            'user' => [
                'update',
                'create',
                'delete',
                'publish',
                'unpublish',
                'available-facets',
                'set-facet-value'
            ]
        ];
    }

    public function actions()
    {
        $actions = parent::actions();
        return $actions;
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
        $item = Item::findOneOr404(['id' => $id]);
        if (!$item->hasModifyRights()) {
            throw new ForbiddenHttpException("Item not yours");
        }
        $item->setScenario("default");
        $item->validate();
        if (!$item->validate()) {
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
        $item = Item::findOneOr404(['id' => $id]);
        if (!$item->hasModifyRights()) {
            throw new ForbiddenHttpException("Item not yours");
        }
        $item->setScenario("default");
        $item->is_available = 0;
        $item->save(false);

        (new ItemSearchDb())->removeItem($item);

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
        if ($item == null) {
            throw new NotFoundHttpException("Item not found");
        }

        $itemFacets = CategoryHasItemFacet::find()
            ->where(['IN', 'category_id', [$item->category_id, $item->category->parent_id]])
            ->innerJoinWith("itemFacet.itemFacetValues")
            ->asArray()
            ->all();

        return $itemFacets;
    }

    public function actionSetFacetValue($id)
    {
        $item = Item::find()->where(['id' => $id])->one();
        if ($item == null) {
            throw new NotFoundHttpException("Item not found");
        }

        /**
         * @var $item Item
         */
        if (!$item->hasModifyRights()) {
            throw new ForbiddenHttpException("Item not yours");
        }
        $data = \Yii::$app->request->getBodyParams();
        $facet_id = $data['facet_id'];

        $f = ItemFacet::find()->where(['id' => $facet_id])->count();
        if ($f == 0) {
            throw new ForbiddenHttpException("Item facet is not found");
        }

        if (isset($data['value_id'])) {
            $f = ItemFacetValue::find()->where(['id' => $data['value_id']])->count();
            if ($f == 0) {
                throw new ForbiddenHttpException("Item facet value is not found");
            }
            $itemHIF = ItemHasItemFacet::find()->where(['item_id' => $id, 'item_facet_id' => $facet_id])->one();
            if ($itemHIF == null) {
                $itemHIF = new ItemHasItemFacet();
                $itemHIF->item_id = $item->id;
                $itemHIF->item_facet_id = $facet_id;
            }
            $itemHIF->item_facet_value_id = $data['value_id'];
            $itemHIF->save();
            return 1;
        } else {
            if (isset($data['value'])) {
                $itemHIF = ItemHasItemFacet::find()->where(['item_id' => $id, 'item_facet_id' => $facet_id])->one();
                if ($data['value'] == 1) {
                    if ($itemHIF !== null) {
                        return 1;
                    }
                    $itemHIF = new ItemHasItemFacet();
                    $itemHIF->item_id = $item->id;
                    $itemHIF->item_facet_id = $facet_id;
                    $itemHIF->save();
                    return 1;
                } else {
                    $itemHIF->delete();
                    return 1;
                }
            } else {
                throw new ForbiddenHttpException("Either a value (boolean) or a value_id should be defined");
            }
        }
    }

    public function actionSearchSuggestions()
    {
        return [
            \Yii::t('item_api.search_suggestion.stroller', 'Stroller'),
            \Yii::t('item_api.search_suggestion.trampolin', 'Trampolin'),
            \Yii::t('item_api.search_suggestion.lego', 'Lego'),
            \Yii::t('item_api.search_suggestion.crib', 'Crib'),
            \Yii::t('item_api.search_suggestion.bike', 'Bike'),
        ];
    }

}