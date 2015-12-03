<?php
namespace api\controllers;

use api\models\Location;

class LocationController extends Controller
{
    public function init(){
        $this->modelClass = Location::className();
        parent::init();
    }

    public function accessControl(){
        return [
            'guest' => ['view'],
            'user' => ['create', 'update']
        ];
    }

    public function actions(){
        $actions = parent::actions();
//        unset($actions['delete']);
//        unset($actions['view']);
//        unset($actions['index']);
//        unset($actions['update']);
//        unset($actions['create']);
        return $actions;
    }
}