<?php

namespace app\tests\codeception\_support;
use FunctionalTester;

use app\modules\user\forms\Login;

class UserHelper
{

    /**
     * Login a user.
     *
     * @param $I FunctionalTester the tester
     * @param $user string the username of the user
     * @param $password string the password of the user
     */
    public static function login($I, $user, $password) {
        // check if elements exist
        $I->amOnPage('/user/logout');
        $I->amOnPage('/user/login');
        $I->canSeeElement('#wrapper #login-form-login');
        $I->canSeeElement('#wrapper #login-form-password');
        $I->fillField('#wrapper #login-form-login', $user);
        $I->fillField('#wrapper #login-form-password', $password);
        $I->click('Sign in');

        // since there is no JavaScript in functional tests, we need to login manually
        $model = \Yii::createObject(Login::className());

        $model->load([
            'login' => $user,
            'password' => $password,
            'rememberMe' => false
        ]);

        echo 'TEST: ' . $model->login();

        $I->amOnPage('/home');
    }

}
