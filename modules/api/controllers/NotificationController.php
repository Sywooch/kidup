<?php
namespace api\controllers;

use api\models\Conversation;
use api\models\Message;
use message\components\MobilePush;

class NotificationController extends Controller
{
    public function init(){
        $this->modelClass = Message::className();
        parent::init();
    }

    public function accessControl(){
        return [
            'guest' => ['register'],
            'user' => []
        ];
    }

    public function actions(){
        $actions = parent::actions();
        unset($actions['delete']);
//        unset($actions['view']);
//        unset($actions['index']);
        unset($actions['update']);
        unset($actions['create']);
        return $actions;
    }

    public function actionRegister($id) {
        (new MobilePush())->registerDevice($id);
    }

}