<?php
namespace api\controllers;

use api\models\Item;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class ItemController extends Controller
{
    public function init(){
        $this->modelClass = Item::className();
        parent::init();
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'authenticator' => [
                'except' => ['index', 'view']
            ],
            'accessControl' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'list'],
                        'roles' => ['?']
                    ],
                ],
            ],
        ]);
    }

    public function actions(){
        $actions = parent::actions();
        unset($actions['delete']);
        unset($actions['create']);
        unset($actions['update']);
        return $actions;
    }

}