<?php
namespace api\controllers;

use api\models\Location;
use user\models\base\IpLocation;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;

class LocationController extends Controller
{
    public function init()
    {
        $this->modelClass = Location::className();
        parent::init();
    }

    public function accessControl()
    {
        return [
            'guest' => ['view', 'get-by-ip'],
            'user' => ['create', 'update', 'index', 'get-by-lng-lat']
        ];
    }

    public function actions()
    {
        $actions = parent::actions();
//        unset($actions['delete']);
//        unset($actions['view']);
        unset($actions['index']);
//        unset($actions['update']);
//        unset($actions['create']);
        return $actions;
    }

    public function actionIndex()
    {
        return new ActiveDataProvider([
            'query' => Location::find()
                ->where(['user_id' => \Yii::$app->user->id])
                ->andWhere('longitude != 0')
        ]);
    }

    public function actionGetByIp($save = true)
    {
        // potential fix for cloudflare user IP adress
        if (YII_ENV !== 'test' && !YII_CONSOLE) {
            if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
                $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            }
            if (isset($_SERVER["REMOTE_ADDR"])) {
                $_SERVER['REMOTE_ADDR'] = $_SERVER["REMOTE_ADDR"];
            }
        }
        $userIp = \Yii::$app->request->getUserIP();
//        $userIp = '83.82.175.173';
        $ipLoc = Location::getByIP($userIp);

        if ($ipLoc == false) {
            throw new BadRequestHttpException("Ip not found");
        }
//        if ($ipLoc->country_code !== 'DK' && $ipLoc->country_code !== 'NL') {
//            throw new BadRequestHttpException("We currently only accept danish locations");
//        }
        if($save !== true){
            $location = new Location();
            $location->latitude = $ipLoc->latitude;
            $location->longitude = $ipLoc->longitude;
            $location->city = $ipLoc->city;
            $location->zip_code = $ipLoc->postal_code;
            $location->country = 1;
            $location->street_name = '';
            $location->street_number = '';
            return $location;
        }
        $userLoc = Location::find()->where([
            'user_id' => \Yii::$app->user->id,
            'longitude' => $ipLoc->longitude,
            'latitude' => $ipLoc->latitude,
        ])->one();
        if (count($userLoc) > 0) {
            return $userLoc;
        }
        $location = new Location();

        $location->latitude = $ipLoc->latitude;
        $location->longitude = $ipLoc->longitude;
        $location->city = $ipLoc->city;
        $location->zip_code = $ipLoc->postal_code;
        $location->country = 1;
        $location->street_name = '';
        $location->street_number = '';
        $location->user_id = \Yii::$app->user->id;
        if ($location->save()) {
            return $location;
        }
        throw new BadRequestHttpException("Location could not be saved");
    }

    public function actionGetByLngLat($lng, $lat)
    {
        return (new Location())->createByLatLong($lat, $lng);
    }
}