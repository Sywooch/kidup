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
     * @param $username string the username of the user
     * @param $password string the password of the user
     */
    public static function login($username, $password)
    {
        if (!Yii::$app->getUser()->getIsGuest()) {
            UserHelper::logout();
        }

        $identity = User::find()->where(['email' => $username])->one();
        Yii::$app->user->login($identity);
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

    /**
     * @param $email
     * @param $password
     * @throws \yii\base\InvalidConfigException
     */
    public static function loginAcceptance($email, $password)
    {
        $user = User::find()->where(['email' => $email])->one();
        \Yii::$app->user->login($user);
    }

    /**
     * Login as an owner.
     */
    public static function loginOwner()
    {
        static::login('owner@kidup.dk', 'testtest');
    }

    /**
     * Login as a renter.
     */
    public static function loginRenter()
    {
        static::login('simon@kidup.dk', 'testtest');
    }

    /**
     * Login as an outsider.
     */
    public static function loginOutsider()
    {
        static::login('ihavenolocation@kidup.dk', 'testtest');
    }
}
