<?php
namespace api\controllers;

use api\models\Item;
use api\models\ItemFacet;
use api\models\Review;
use item\models\base\CategoryHasItemFacet;
use search\forms\Filter;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;

class ItemFacetController extends Controller
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
        $this->modelClass = ItemFacet::className();
        parent::init();
    }

    public function accessControl()
    {
        return [
            'guest' => ['index', 'view'],
            'user' => ['available-for-item']
        ];
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete']);
        unset($actions['update']);
        unset($actions['create']);
        return $actions;
    }

    /**
     * @api {get}                   item-facets/available-for-item/:item_id
     * @apiName                     publishItem
     * @apiGroup                    Item
     * @apiDescription              Get all the possible features + possible values
     */
    public function actionAvailableForItem($id)
    {
//        $item = Item::find()->where(['id' => $id])->one();
//        if($item == null){
//            throw new NotFoundHttpException("Item not found");
//        }
//
//        $itemFacets = CategoryHasItemFacet::find()
//            ->where(['IN', 'category_id', [$item->category_id, $item->category->parent_id]])
//            ->select('item_facet_id')
//            ->asArray()
//            ->all();
//
//        $res = [];
//        foreach ($itemFacets as $itemFacet) {
//            $res[] = $itemFacet['item_facet_id'];
//        }

        return new ActiveDataProvider([
            'query' => ItemFacet::find()
                ->where(["IN", 'id', [4,7,8,9]])
        ]);
    }

}