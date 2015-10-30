<?php
namespace api\controllers;

use admin\models\TrackingEvent;
use app\extended\web\Controller;
use item\models\Location;
use yii\web\BadRequestHttpException;
use yii\web\UnauthorizedHttpException;
use Yii;

class EventController extends Controller
{
    public function actionIndex($type, $data = null, $t = null){
        $detect = new \Mobile_Detect();
        $device = 0;
        if($detect->isMobile()){
            $device = 1;
        }
        if($detect->isTablet()){
            $device = 2;
        }
        $country = null;
        $city = null;
        $location = Location::getByIP(\Yii::$app->request->getUserIP());
        if($location){
            $country = $location->country_code;
            $city = $location->city;
        }
        TrackingEvent::track($type, $data, $country, $city, $device, $t);
        exit();
    }
}