<?php
namespace api\v2\controllers;

use api\v2\models\Message;
use message\models\message\MessageFactory;

class MessageController extends Controller
{
    public function init()
    {
        $this->modelClass = Message::className();
        parent::init();
    }

    public function accessControl()
    {
        return [
            'guest' => [],
            'user' => ['create', 'view', 'index', 'options', 'unread-count']
        ];
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete']);
//        unset($actions['view']);
//        unset($actions['index']);
        unset($actions['update']);
        unset($actions['create']);
        return $actions;
    }

    public function actionCreate()
    {
        $params = \Yii::$app->getRequest()->getBodyParams();
        $factory = new MessageFactory();
        return $factory->createForApi($params, $this->modelClass);
    }

}