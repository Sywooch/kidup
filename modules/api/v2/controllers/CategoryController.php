<?php
namespace api\v2\controllers;

use api\v2\models\Category;
use yii\data\ActiveDataProvider;

class CategoryController extends Controller
{
    public function init()
    {
        $this->modelClass = Category::className();
        parent::init();
    }

    public function accessControl()
    {
        return [
            'guest' => ['index', 'view'],
            'user' => []
        ];
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete']);
        unset($actions['update']);
        unset($actions['create']);
        unset($actions['index']);
        return $actions;
    }

    public function actionIndex()
    {
        return new ActiveDataProvider([
            'query' => Category::find(),
            'pagination' => false
        ]);
    }
}