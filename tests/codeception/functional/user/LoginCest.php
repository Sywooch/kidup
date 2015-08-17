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
     * Initialize the test.
     *
     * @param functionalTester $I
     */
    public function _before(functionalTester $I)
    {
        (new FixtureHelper)->fixtures();
    }

    public function checkRegister(functionalTester $I)
    {
        $I->wantTo('ensure that I can register');
        $I->amOnPage('/user/register');
        $I->canSeeElement('#register-form-email');
        $I->canSeeElement('#register-form-password');
        $I->see('Sign up');
        // make sure we don't fill in the modal
        $I->fillField('#wrapper #register-form-email', 'idont@exist.com');
        $I->fillField('#wrapper #register-form-password', 'testtest');
        $I->click('Sign up', '#wrapper #registration-form > button');
        $I->seeInCurrentUrl('/user/registration/post-registration');
        $this->checkPostRegistration($I);
    }

    private function checkPostRegistration(functionalTester $I){
        $I->see("We'd like to get to know you!");
        $I->seeElement('#post-registration-form-firstname');
        $I->seeElement('#post-registration-form-lastname');
        $I->fillField('#post-registration-form-firstname', 'User');
        $I->fillField('#post-registration-form-lastname', 'Test');
        $I->fillField('#post-registration-form-description', 'Description');
        $I->click('Complete');
        $I->amOnPage('/register'); // goes back to page where it started, register in this case
        $I->canSeeLink("Log Out");
        $I->click("Log Out");
    }

    /**
     * Test whether login works.
     *
     * @param functionalTester $I
     */
    public function checkLogin(functionalTester $I)
    {
        $I->wantTo('ensure that I can login');
        UserHelper::login($I, 'simon@kidup.dk', 'testtest');
        $I->canSee('Log Out');
    }

}

?>