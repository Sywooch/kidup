<?php
namespace app\tests\codeception\functional\user;

use app\tests\codeception\_support\UserHelper;
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
     * Test whether login works.
     *
     * @param functionalTester $I
     */
    public function checkLogin(functionalTester $I)
    {
        $I->wantTo('ensure that I can login');
        UserHelper::login($I, 'simon@kidup.dk', 'testtest');
    }

}

?>