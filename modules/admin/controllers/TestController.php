<?php

namespace admin\controllers;

use app\helpers\Event;
use user\models\User;

class TestController extends Controller
{

    public function actionMail(){
        $user = User::findOne(['email' => 'simon@tuple.nl']);
        Event::trigger($user, User::EVENT_USER_REGISTER_DONE);
    }
}
