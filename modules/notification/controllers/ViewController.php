<?php
namespace notification\controllers;

use app\extended\web\Controller;
use app\helpers\Event;
use booking\models\booking\Booking;
use notification\components\MailRenderer;
use notification\components\MailSender;
use notification\components\NotificationDistributer;
use notification\models\base\MobileDevices;
use user\models\User;
use Yii;
use yii\web\NotFoundHttpException;

class ViewController extends Controller
{

    public $layout;

    public function actionIndex()
    {
        $booking = Booking::find()->one();
        $res = (new NotificationDistributer($booking->renter_id))->bookingDeclinedRenter($booking);
    }

    public function actionLink($url, $mailId)
    {
        return \Yii::$app->view->renderFile('@notification-layouts/link.twig', [
            'url' => base64_decode($url),
            'appstore_google_url' => Yii::$app->params['appstore-google'],
            'appstore_ios_url' => Yii::$app->params['appstore-ios'],
        ]);
    }

    public function actionTest()
    {
        $m = new MobileDevices();
        $m->user_id = 1;
        $m->last_activity_at = time();
        $m->created_at = time();
        $m->is_subscribed = 1;
        $m->device_id = uniqid();
        $m->token = uniqid();
        $m->platform = 'test';
        \yii\helpers\VarDumper::dump($m->save(), 10, true);
        exit();
    }
}

