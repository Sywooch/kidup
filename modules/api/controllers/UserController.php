<?php
namespace api\controllers;

use api\models\User;
use yii\web\BadRequestHttpException;
use yii\web\UnauthorizedHttpException;

class UserController extends Controller
{
    public function init(){
        $this->modelClass = User::className();
        parent::init();
    }

    public function accessControl(){
        return [
            'guest' => ['index', 'view', 'create'],
            'user' => ['update']
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