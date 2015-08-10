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
     * Test whether login works.
     *
     * @param AcceptanceTester $I
     */
    public function checkLogin(AcceptanceTester $I)
    {
        $I->wantTo('ensure that I can login');
        $I->amOnPage('/');
    }

}

?>