<?php

namespace app\tests\codeception\_support;
use FunctionalTester;

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
        $I->amOnPage('/user/login');
        $I->canSeeElement('#wrapper #login-form-login');
        $I->canSeeElement('#wrapper #login-form-password');
        $I->fillField('#wrapper #login-form-login', $user);
        $I->fillField('#wrapper #login-form-password', $password);
        $I->click('Sign in');
    }

}
