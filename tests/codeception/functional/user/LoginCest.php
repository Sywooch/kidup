<?php
namespace codecept\functional\user;

use codecept\_support\MuffinHelper;
use codecept\_support\MuffinTrait;
use codecept\_support\UserHelper;
use codecept\muffins\UserMuffin;
use functionalTester;
use League\FactoryMuffin\FactoryMuffin;

/**
 * functional test for the login.
 *
 * Class LoginCest
 * @package codecept\functional\user
 */
class LoginCest
{
    protected $user;
    /**
     * @var FactoryMuffin
     */
    protected $fm = null;

    public function _before() {
        $this->fm = (new MuffinHelper())->init();
        $this->user = $this->fm->create(UserMuffin::class, [
            'password_hash' => \Yii::$app->security->generatePasswordHash('testtest')
        ]);
    }

    public function checkLogin(functionalTester $I)
    {
        $I->wantTo('ensure that I can login trough the login page');
        UserHelper::logout();
        $I->assertTrue(\Yii::$app->getUser()->getIsGuest(), 'I should be a guest now');
        $I->amOnPage('/user/login');
        $I->see('Login to KidUp');
        $I->fillField("login-form[login]", $this->user->email);
        $I->fillField("login-form[password]", 'testtest');
        $I->see("Sign in");
        $I->click("Sign in");
        $I->assertTrue(\Yii::$app->getUser()->getIsGuest(), 'I should not be a guest now');
    }

    public function checkLogout(functionalTester $I){
        UserHelper::login($this->user);
        $I->amOnPage('/home/home/index');
        $I->canSee('Log Out');
        $I->click('Log Out');
//        $I->amOnPage('/home');
//        $I->dontSee('Log Out');
    }
}

?>