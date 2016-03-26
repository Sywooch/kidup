<?php
namespace api\controllers;

use admin\models\TrackingEvent;
use api\models\oauth\OauthAccessToken;
use app\extended\web\Controller;
use item\models\location\LocationFactory;
use Yii;

class EventController extends Controller
{
    public function actionIndex($type, $data = null, $t = null, $s = null, $l = null, $m = null, $uuid = null)
    {
        $country = null;
        $city = null;
        $location = LocationFactory::createByIp(\Yii::$app->request->getUserIP());
        if ($location) {
            $country = $location->country0->name;
            $city = $location->city;
        }
        TrackingEvent::track($type, $data, $country, $city, $m, $t, $l, $s, \Yii::$app->request->getUserIP(), $uuid);
        return '';
    }

    public function actionError($data)
    {
        if (isset($_GET['access-token'])) {
            \Yii::$app->user->loginByAccessToken($_GET['access-token']);
        }
        \Yii::error(urldecode($data), "kidup-app");
    }
}