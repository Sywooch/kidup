<?php
namespace api\v2\controllers;

use admin\models\TrackingEvent;
use user\oauthAccessToken\OauthAccessToken;
use app\components\web\Controller;
use item\models\location\LocationFactory;
use Yii;

class EventController extends Controller
{
    public function actionIndex($type, $data = null, $t = null, $s = null, $l = null, $m = null, $uuid = null)
    {
        \Yii::$app->response->headers->add("Access-Control-Allow-Origin", "*");
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
        \Yii::$app->response->headers->add("Access-Control-Allow-Origin", "*");
        if (isset($_GET['access-token'])) {
            \Yii::$app->user->loginByAccessToken($_GET['access-token']);
        }
        $data = urldecode($data);
        $data = str_replace("\\n", PHP_EOL, $data);
        \Yii::error($data, "kidup-app");
    }
}