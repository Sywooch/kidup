<?php
namespace api\controllers;

use api\models\Location;
use yii\data\ActiveDataProvider;

class LocationController extends Controller
{
    public function init(){
        $this->modelClass = Location::className();
        parent::init();
    }

    public function accessControl(){
        return [
            'guest' => ['view'],
            'user' => ['create', 'update', 'index']
        ];
    }

    public function actions(){
        $actions = parent::actions();
//        unset($actions['delete']);
//        unset($actions['view']);
        unset($actions['index']);
//        unset($actions['update']);
//        unset($actions['create']);
        return $actions;
    }

    public function actionIndex(){
        return new ActiveDataProvider([
            'query' => Location::find()->where(['user_id' => \Yii::$app->user->id])
        ]);
    }
}