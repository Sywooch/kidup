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
        $I->assertFalse(\Yii::$app->getUser()->getIsGuest(), 'I should be logged in now, but I am not');
    }

    public function checkLogout(functionalTester $I){
        $I->amOnPage('/home');
        $I->canSee('Log Out');
        $I->click('Log Out');
        $I->amOnPage('/home');
        $I->dontSee('Log Out');
    }
}

?>