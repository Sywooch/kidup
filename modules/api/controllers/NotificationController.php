<?php
namespace api\controllers;

use api\models\Message;
use message\components\MobilePush;
use Aws;
use message\models\base\MobileDevices;

class NotificationController extends Controller
{
    public function init(){
        $this->modelClass = Message::className();
        parent::init();
    }

    public function accessControl(){
        return [
            'guest' => ['register', 'subscribe', 'unsubscribe', 'test'],
            'user' => ['set-user']
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
        MobileDevices::deleteAll(['device_id' => $params['device_id']]);
        return (new MobilePush())->registerDevice($params['device_id'], $params['token'], $params['platform']);
    }

    public function actionSubscribe() {
        $params = \Yii::$app->getRequest()->getBodyParams();
        $deviceId = $params['device_id'];
        $device = MobileDevices::find()->where(['device_id' => $deviceId])->one();
        $device->is_subscribed = true;
        return $device->save();
    }

    public function actionUnsubscribe() {
        $params = \Yii::$app->getRequest()->getBodyParams();
        $deviceId = $params['device_id'];
        $device = MobileDevices::find()->where(['device_id' => $deviceId])->one();
        $device->is_subscribed = false;
        return $device->save();
    }

    public function actionSetUser() {
        $params = \Yii::$app->getRequest()->getBodyParams();
        $userId = \Yii::$app->getUser()->id;
        $deviceId = $params['device_id'];
        $device = MobileDevices::find()->where(['device_id' => $deviceId])->one();
        $device->user_id = $userId;
        return $device->save();
    }

    public function actionTest() {
        // Send a message to user with user Id 2
        $device = MobileDevices::find()->where(['user_id' => 2, 'is_subscribed' => true])->one();
        $arn = $device->endpoint_arn;
        (new MobilePush())->sendMessage($arn, 'Hello world');
    }

}