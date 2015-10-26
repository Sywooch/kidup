<?php
namespace api\controllers;

use api\models\Item;
use yii\data\ActiveDataProvider;

class ItemController extends Controller
{
    public function init(){
        $this->modelClass = Item::className();
        parent::init();
    }

    public function accessControl(){
        return [
            'guest' => ['index', 'view'],
            'user' => []
        ];
    }

    public function actions(){
        $actions = parent::actions();
        unset($actions['delete']);
        unset($actions['create']);
        unset($actions['index']);
        unset($actions['update']);
        return $actions;
    }

    public function actionIndex(){
        return new ActiveDataProvider([
            'query' => Item::find()->where(['is_available' => 1])
        ]);
    }
}