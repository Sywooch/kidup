<?php
namespace api\controllers;

use item\models\item\Item;
use item\models\WishListItem\WishListItem;
use item\models\WishListItem\WishListItemApi;
use item\models\wishListItem\WishListItemFactory;
use user\models\User;
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
        unset($actions['view']);
        unset($actions['index']);
        return $actions;
    }

    public function actionIndex()
    {
        return new ActiveDataProvider([
            'query' => WishListItem::find()->where(['user_id' => \Yii::$app->user->id])
        ]);
    }

    public function actionCreate()
    {
        $params = \Yii::$app->request->post();
        $user = User::findOneOr404(\Yii::$app->user->id);
        $item = Item::findOneOr404($params['item_id']);
        return (new WishListItemFactory())->create($user, $item);
    }
}