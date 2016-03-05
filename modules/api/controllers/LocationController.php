<?php
namespace api\controllers;

use api\models\Location;
use item\models\location\LocationFactory;
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
        unset($actions['index']);
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
        $location = LocationFactory::createByIp($userIp);

        if ($location == false) {
            throw new BadRequestHttpException("Ip not found");
        }
        if($save !== true){
            return $location;
        }
        $userLoc = Location::find()->where([
            'user_id' => \Yii::$app->user->id,
            'longitude' => $location->longitude,
            'latitude' => $location->latitude,
        ])->one();
        if (count($userLoc) > 0) {
            return $userLoc;
        }
        $location->user_id = \Yii::$app->user->id;
        if ($location->save()) {
            return $location;
        }
        throw new BadRequestHttpException("Location could not be saved");
    }

    public function actionGetByLngLat($lng, $lat)
    {
        return LocationFactory::createByLatLong($lat, $lng);
    }
}