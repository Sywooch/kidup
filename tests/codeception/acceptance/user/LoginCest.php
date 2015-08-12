<?php
namespace app\tests\codeception\acceptance\user;

use AcceptanceTester;
use app\tests\codeception\_support\FixtureHelper;

/**
 * Acceptance test for the login.
 *
 * Class LoginCest
 * @package app\tests\codeception\acceptance\user
 */
class LoginCest
{

    /**
     * Initialize the test.
     *
     * @param AcceptanceTester $I
     */
    public function _before(AcceptanceTester $I)
    {
        (new FixtureHelper)->fixtures();
    }

    /**
     * Test whether does not work when wrong credentials are provided.
     *
     * @param AcceptanceTester $I
     */
    public function checkLoginWrongCredentials(AcceptanceTester $I)
    {
        $I->wantTo('ensure that I can not login with wrong credentials');
        $I->amOnPage('/');
        $I->resizeWindow(1024, 500);
        $I->click('#login');
        $I->wait(2);
        $I->dontSeeElement('.has-error');
        $I->fillField('#login-form-login', 'test');
        $I->fillField('#login-form-password', 'test');
        $I->click('button[type=submit]');
        $I->wait(2);
        $I->canSeeElement('.has-error');
    }

}

?>