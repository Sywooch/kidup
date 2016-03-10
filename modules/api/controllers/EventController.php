<?php
namespace api\controllers;

use admin\models\TrackingEvent;
use app\extended\web\Controller;
use item\models\location\LocationFactory;
use Yii;

class EventController extends Controller
{
    public function actionIndex($type, $data = null, $t = null, $s = null, $l = null, $m = null, $uuid = null)
    {
        $detect = new \Mobile_Detect();
        // not sure why this is here
        if (\Yii::$app->session->gCProbability) {
            if ($detect->is("Bot") || $detect->is("MobileBot")) {
                // don't track bots
                exit();
            }
        }
        $country = null;
        $city = null;
        $location = LocationFactory::createByIp(\Yii::$app->request->getUserIP());
        if ($location) {
            $country = $location->country;
            $city = $location->city;
        }
        TrackingEvent::track($type, $data, $country, $city, $m, $t, $l, $s, \Yii::$app->request->getUserIP(), $uuid);
        return '';
    }
}