<?php

namespace app\tests\codeception\_support;

use AcceptanceTester;
use app\modules\user\models\User;
use FunctionalTester;
use Yii;

class UserHelper
{

    /**
     * Login a user.
     *
     * @param User the user to login
     */
    public static function login($user)
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
