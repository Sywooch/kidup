<?php
namespace api\controllers;

use api\models\Category;
use api\models\Item;
use item\controllers\ViewController;
use api\models\Review;
use search\forms\Filter;
use yii\data\ActiveDataProvider;
use yii\web\HttpException;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;

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