<?php

namespace app\tests\codeception\_support;
use app\modules\user\forms\Login;
use FunctionalTester;
use AcceptanceTester;

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
        $model = \Yii::createObject(Login::className());
        $model->load([
            'login' => $user,
            'password' => $password
        ]);
        $model->login();
    }

}
