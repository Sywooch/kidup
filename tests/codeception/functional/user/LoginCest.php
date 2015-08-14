<?php
namespace app\tests\codeception\functional\user;

use functionalTester;
use app\tests\codeception\_support\FixtureHelper;

/**
 * functional test for the login.
 *
 * Class LoginCest
 * @package app\tests\codeception\functional\user
 */
class LoginCest
{

    /**
     * Initialize the test.
     *
     * @param functionalTester $I
     */
    public function _before(functionalTester $I)
    {
        (new FixtureHelper)->fixtures();
    }

    /**
     * Test whether login works.
     *
     * @param functionalTester $I
     */
    public function checkLogin(functionalTester $I)
    {
        $I->wantTo('ensure that I can login');
        $I->amOnPage('/user/login');
        $I->canSeeElement('#login-form-login');
        $I->canSeeElement('#login-form-password');
        $I->fillField('#login-form-login', 'simon@kidup.dk');
        $I->fillField('#login-form-password', 'testtest');
        $I->click('Sign in');
    }

}

?>