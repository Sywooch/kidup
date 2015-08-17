<?php

namespace app\tests\codeception\_support;

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
        $I->amOnPage('/user/login');
        $I->canSeeElement('#login-form-login');
        $I->canSeeElement('#login-form-password');
        $I->fillField('#login-form-login', $user);
        $I->fillField('#login-form-password', $password);
        $I->click('Sign in');
    }

}
