<?php
namespace api\controllers;

use api\models\Message;
use Aws;
use booking\models\booking\BookingException;
use message\components\MobilePush;
use message\models\conversation\Conversation;
use notification\components\NotificationDistributer;
use notification\models\base\MobileDevices;
use notification\models\NotificationMailLog;
use user\models\user\User;
use yii\base\Exception;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class NotificationController extends Controller
{
    public function init()
    {
        $this->modelClass = Message::className();
        parent::init();
    }

    public function accessControl()
    {
        return [
            'guest' => ['register', 'subscribe', 'unsubscribe', 'is-subscribed', 'test', 'mail-click', 'mail-view'],
            'user' => ['set-user']
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

    public function actionRegister()
    {
        $params = \Yii::$app->getRequest()->getBodyParams();
        MobileDevices::deleteAll(['device_id' => $params['device_id']]);
        return (new MobilePush())->registerDevice($params['device_id'], $params['token'], $params['platform']);
    }

    public function actionSubscribe()
    {
        $params = \Yii::$app->getRequest()->getBodyParams();
        $device = MobileDevices::findOneOr404(['device_id' => $params['device_id']]);
        $device->is_subscribed = true;
        return $device->save();
    }

    public function actionUnsubscribe()
    {
        $params = \Yii::$app->getRequest()->getBodyParams();
        $device = MobileDevices::findOneOr404(['device_id' => $params['device_id']]);
        $device->is_subscribed = false;
        return $device->save();
    }

    public function actionSetUser()
    {
        $params = \Yii::$app->getRequest()->getBodyParams();
        $userId = \Yii::$app->getUser()->id;
        $deviceId = $params['device_id'];
        $device = MobileDevices::find()->where(['device_id' => $deviceId])->one();
        if ($device == null) {
            throw new NotFoundHttpException("Device not found");
        }
        $device->user_id = $userId;
        return $device->save();
    }

    public function actionIsSubscribed()
    {
        $params = \Yii::$app->getRequest()->getBodyParams();
        $deviceId = $params['device_id'];
        $devices = MobileDevices::find()->where(['device_id' => $deviceId])->all();
        if (count($devices) == 0) {
            return ['is_subscribed' => false];
        }
        foreach ($devices as $device) {
            return ['is_subscribed' => $device['is_subscribed']];
        }
    }

    public function actionTest()
    {
        $user = User::find()->where(['email' => 'kevin91nl@gmail.com'])->one();
        (new NotificationDistributer($user->id, true))->userRecovery($user, 'recovery-url');
        die();
        try {
            try {
                throw new BookingException("Kut!");
            } catch (Exception $e) {
                throw new BookingException("poes", null, $e);
            }
        } catch (Exception $e) {
            \Yii::error($e);
            throw new BadRequestHttpException("Went wrong!");
        }
    }

    public function actionMailView($hash) {
        $mail = NotificationMailLog::findOneOr404(['hash' => $hash]);
        echo $mail->view;
    }

    public function actionMailClick() {
        echo 'test456';
    }

}