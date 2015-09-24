<?php
namespace app\tests\codeception\functional\user;

use app\tests\codeception\_support\MuffinHelper;
use app\tests\codeception\_support\UserHelper;
use app\tests\codeception\muffins\Item;
use app\tests\codeception\muffins\Profile;
use app\tests\codeception\muffins\Token;
use app\tests\codeception\muffins\User;
use Codeception\Util\Debug;
use functionalTester;
use League\FactoryMuffin\FactoryMuffin;
use League\FactoryMuffin\Faker\Facade as Faker;

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
        /**
         * @var FactoryMuffin $fm
         */
        $fm = (new MuffinHelper())->init()->getFactory();
        $user = $fm->create(User::className());
        $items = $fm->seed(5, Item::className(), ['owner_id' => $user->id]);
        Debug::debug($items[0]->owner_id);
//        $I->assertTrue($user->id === $items[0]->owner_id);

        $I->wantTo('ensure that I can login');
        UserHelper::login($user);
        $I->assertFalse(\Yii::$app->getUser()->getIsGuest(), 'I should not be a guest now');
    }

//    public function checkLogout(functionalTester $I){
//        $I->amOnPage('/home');
//        $I->canSee('Log Out');
//        $I->click('Log Out');
//        $I->amOnPage('/home');
//        $I->dontSee('Log Out');
//    }
}

?>