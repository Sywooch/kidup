<?php

namespace admin\controllers;

use app\helpers\Event;
use item\models\location\Location;
use user\models\user\User;

class TestController extends Controller
{

    public function actionMail(){
        $user = User::findOne(['email' => 'simon@tuple.nl']);
        Event::trigger($user, User::EVENT_USER_REGISTER_DONE);
    }

    public function actionTest(){
        $location = Location::addressToLngLat('Aarhus Denmark');
        \yii\helpers\VarDumper::dump($location,10,true); exit();
    }
}
