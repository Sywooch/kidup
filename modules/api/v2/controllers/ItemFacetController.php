<?php
namespace api\v2\controllers;

use api\v2\models\ItemFacet;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

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

        return new ActiveDataProvider([
            'query' => ItemFacet::find()
                ->where(["IN", 'id', [4,7,8,9]])
        ]);
    }

}