<?php
namespace api\controllers;

use api\models\Message;
use message\components\MobilePush;
use Aws;

class NotificationController extends Controller
{
    public function init(){
        $this->modelClass = Message::className();
        parent::init();
    }

    public function accessControl(){
        return [
            'guest' => ['register', 'test'],
            'user' => ['register', 'unregister', 'set-user']
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

    public function actionRegister() {
        $params = \Yii::$app->getRequest()->getBodyParams();
        (new MobilePush())->registerDevice($params['device_id'], $params['token'], $params['platform']);
    }

    public function actionUnregister() {

    }

}