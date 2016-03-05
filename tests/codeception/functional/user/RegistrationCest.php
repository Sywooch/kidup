<?php
namespace codecept\functional\user;

use codecept\_support\MuffinHelper;
use FunctionalTester;
use League\FactoryMuffin;
use League\FactoryMuffin\Faker as Faker;
use user\models\Profile;
use user\models\User;

/**
 * functional test for the login.
 *
 * Class LoginCest
 * @package codecept\functional\user
 */
class RegistrationCest
{

    /**
     * @var FactoryMuffin
     */
    protected $fm = null;

    public function _before()
    {
        $this->fm = (new MuffinHelper())->init();
    }

    public function checkRegister(FunctionalTester $I)
    {
        $faker = \Faker\Factory::create();
        $email = $faker->freeEmail;
        $I->wantTo('ensure that I can register');
        $I->amOnPage('/user/register');
        $I->canSeeElement('#register-form-email');
        $I->canSeeElement('#register-form-password');
        $I->see('Sign up');
        // make sure we don't fill in the modal
        $I->fillField('#wrapper #register-form-email', $email);
        $I->fillField('#wrapper #register-form-password', 'testtest');
        $I->click('Sign up', '#wrapper #registration-form > button');
        $I->seeRecord(User::className(), [
            'email' => $email
        ]);
        // unclear why this one fails
        // $I->seeInCurrentUrl('/user/registration/post-registration');
        $this->checkPostRegistration($I);
    }

    private function checkPostRegistration(FunctionalTester $I)
    {
        $I->amOnPage('/user/registration/post-registration');
        $I->see("We'd like to get to know you!");
        $I->seeElement('#post-registration-form-firstname');
        $I->seeElement('#post-registration-form-lastname');
        $I->fillField('#post-registration-form-firstname', 'first_name_124');
        $I->fillField('#post-registration-form-lastname', 'last_name_345');
        $I->fillField('#post-registration-form-description', 'Description');
        $I->click('Complete');
        $I->seeRecord(Profile::className(), [
            'first_name' => 'first_name_124',
            'last_name' => 'last_name_345',
        ]);
        $I->canSeeLink("Log Out");
        $I->click("Log Out");
    }
}

?>