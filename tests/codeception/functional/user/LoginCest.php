<?php
namespace app\tests\codeception\functional\user;

use app\tests\codeception\_support\MuffinHelper;
use app\tests\codeception\_support\UserHelper;
use app\tests\codeception\muffins\Item;
use app\tests\codeception\muffins\User;
use Codeception\Util\Debug;
use functionalTester;
use League\FactoryMuffin\FactoryMuffin;

/**
 * functional test for the login.
 *
 * Class LoginCest
 * @package app\tests\codeception\functional\user
 */
class LoginCest
{

    /**
     * @var FactoryMuffin
     */
    protected $fm;
    protected $user;

    public function _before() {
        $this->fm = (new MuffinHelper())->init();
        $this->user = $this->fm->create(User::class, [
            'password_hash' => \Yii::$app->security->generatePasswordHash('testtest')
        ]);
    }

    public function checkLogin(functionalTester $I)
    {
        UserHelper::logout();

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
        $I->amOnPage('/home');
        $I->canSee('Log Out');
        $I->click('Log Out');
        $I->amOnPage('/home');
        $I->dontSee('Log Out');
    }
}

?>