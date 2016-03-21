<?php
namespace api\controllers;

use api\models\Message;
use Aws;
use message\components\MobilePush;
use message\models\conversation\Conversation;
use notification\components\NotificationDistributer;
use notification\models\base\MobileDevices;
use user\models\User;
use yii\web\NotFoundHttpException;

class NotificationController extends Controller
{
    public function init(){
        $this->modelClass = Message::className();
        parent::init();
    }

    public function accessControl(){
        return [
            'guest' => ['register', 'subscribe', 'unsubscribe', 'is-subscribed', 'test'],
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
        if ($device == null){
            throw new NotFoundHttpException("Device not found");
        }
        $device->is_subscribed = true;
        return $device->save();
    }

    public function actionUnsubscribe() {
        $params = \Yii::$app->getRequest()->getBodyParams();
        $deviceId = $params['device_id'];
        $device = MobileDevices::find()->where(['device_id' => $deviceId])->one();
        if($device == null){
            throw new NotFoundHttpException("Device not found");
        }
        $device->is_subscribed = false;
        return $device->save();
    }

    public function actionSetUser() {
        $params = \Yii::$app->getRequest()->getBodyParams();
        $userId = \Yii::$app->getUser()->id;
        $deviceId = $params['device_id'];
        $device = MobileDevices::find()->where(['device_id' => $deviceId])->one();
        if($device == null){
            throw new NotFoundHttpException("Device not found");
        }
        $device->user_id = $userId;
        return $device->save();
    }

    public function actionIsSubscribed() {
        $params = \Yii::$app->getRequest()->getBodyParams();
        $deviceId = $params['device_id'];
        $devices = MobileDevices::find()->where(['device_id' => $deviceId])->all();
        if (count($devices) == 0) return ['is_subscribed' => false];
        foreach ($devices as $device) {
            return ['is_subscribed' => $device['is_subscribed']];
        }
    }

    public function actionTest() {
        /*$parameters = [
            'state' => 'app.create-booking',
            'params' => [
                'itemId' => 6
            ]
        ];
        $parameters = [
            'state' => 'app.location'
        ];

        // Send a message to user with user Id 2
        $devices = MobileDevices::find()->where(['user_id' => 5610, 'is_subscribed' => true])->all();
        foreach ($devices as $device) {
            $arn = $device->endpoint_arn;
            (new MobilePush())->sendMessage($arn, 'Hello world 2', $parameters);
        }*/

        $message = Message::find()->where(['receiver_user_id' => 1])->one();

        (new NotificationDistributer(1))->conversationMessageReceived($message);
        //(new NotificationDistributer($simon->id))->userWelcome($simon);
        return ['success' => true];
    }

}