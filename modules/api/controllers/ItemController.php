<?php
namespace api\controllers;

use api\models\oauth\Item;

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
        unset($actions['update']);
//        unset($actions['view']);
        return $actions;
    }

    public function actionView($id){
        return Item::findOne($id);
    }

}