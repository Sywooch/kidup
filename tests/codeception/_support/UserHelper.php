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
     * @param $I FunctionalTester|AcceptanceTester the tester
     * @param $user string the username of the user
     * @param $password string the password of the user
     */
    public static function login($I, $user, $password) {
        if (Yii::$app->getUser()->getIsGuest()) {
            $I->amOnPage('/user/login');
            $I->canSeeElement('#wrapper #login-form-login');
            $I->canSeeElement('#wrapper #login-form-password');
            $I->fillField('#wrapper #login-form-login', $user);
            $I->fillField('#wrapper #login-form-password', $password);
            $I->click('#wrapper #login-form button[type=submit]');
        }
        $I->amOnPage('/home');
        $I->canSee('Log Out');
    }

    /**
     * @param $user
     * @param $password
     * @throws \yii\base\InvalidConfigException
     */
    public static function loginAcceptance($user, $password) {
        $user = User::find()->where(['email' => $user])->one();
        \Yii::$app->user->login($user);
    }

}
