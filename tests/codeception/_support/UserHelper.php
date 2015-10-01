<?php

namespace app\tests\codeception\_support;

use AcceptanceTester;
use \user\models\User;
use FunctionalTester;
use Yii;

class UserHelper
{

    /**
     * Login a user.
     */
    public static function login(User $user)
    {
        if (!Yii::$app->getUser()->getIsGuest()) {
            UserHelper::logout();
        }

        Yii::$app->user->login($user);
        Yii::$app->session->set('lang', 'en');
    }


    /**
     * Logout a user.
     *
     * @param  FunctionalTester|AcceptanceTester the tester
     */
    public static function logout()
    {
        \Yii::$app->user->logout();
    }

}
