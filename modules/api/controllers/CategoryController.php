<?php
namespace api\controllers;

use api\models\Category;

class CategoryController extends Controller
{
    public function init(){
        $this->modelClass = Category::className();
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
        return $actions;
    }
}