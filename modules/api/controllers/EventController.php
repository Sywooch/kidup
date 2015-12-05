<?php
namespace api\controllers;

use admin\models\TrackingEvent;
use app\extended\web\Controller;
use item\models\Location;
use Yii;

class EventController extends Controller
{
    public function actionIndex($type, $data = null, $t = null, $s = null, $l = null, $m = null)
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
        $location = Location::getByIP(\Yii::$app->request->getUserIP());
        if ($location) {
            $country = $location->country_code;
            $city = $location->city;
        }
        TrackingEvent::track($type, $data, $country, $city, $m, $t, $l, $s, \Yii::$app->request->getUserIP());
        return '';
    }
}