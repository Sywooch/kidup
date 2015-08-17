<?php

namespace app\tests\codeception\_support;

use app\modules\user\forms\Login;

class UserHelper
{

    /**
     * Login a user.
     *
     * @param $I the tester
     * @param $user the username of the user
     * @param $password the password of the user
     */
    public static function login($I, $user, $password) {
        // check if elements exist
        $I->amOnPage('/user/logout');
        $I->amOnPage('/user/login');
        $I->canSeeElement('#login-form-login');
        $I->canSeeElement('#login-form-password');
        $I->fillField('#login-form-login', $user);
        $I->fillField('#login-form-password', $password);
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
