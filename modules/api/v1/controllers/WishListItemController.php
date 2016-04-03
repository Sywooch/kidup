<?php
namespace api\v1\controllers;

use item\models\item\Item;
use item\models\wishListItem\WishListItem;
use item\models\wishListItem\WishListItemApi;
use item\models\wishListItem\WishListItemFactory;
use user\models\user\User;
use yii\data\ActiveDataProvider;

class WishListItemController extends Controller
{
    public function init()
    {
        $this->modelClass =  WishListItemApi::className();
        parent::init();
    }

    public function accessControl()
    {
        return [
            'guest' => [],
            'user' => ['index', 'create', 'delete']
        ];
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);
        unset($actions['view']);
        unset($actions['index']);
        return $actions;
    }

    public function actionIndex()
    {
        return new ActiveDataProvider([
            'query' => WishListItemApi::find()->where(['user_id' => \Yii::$app->user->id])
        ]);
    }

    public function actionCreate()
    {
        $params = \Yii::$app->request->post();
        $user = User::findOneOr404(\Yii::$app->user->id);
        $item = Item::findOneOr404($params['item_id']);
        return (new WishListItemFactory())->create($user, $item);
    }

    public function actionDelete($id){
        $w = WishListItem::findOneOr404([
            'user_id' => \Yii::$app->user->id,
            'item_id' => $id
        ]);
        return $w->delete();
    }
}