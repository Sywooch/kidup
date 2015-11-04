<?php
use codecept\_support\MuffinHelper;
use codecept\muffins\User;
use League\FactoryMuffin\FactoryMuffin;

/**
 * functional test for the login.
 *
 * Class RegisterCest
 * @package codecept\api\user
 */
class RegisterCest
{
    protected $user;
    /**
     * @var FactoryMuffin
     */
    protected $fm = null;

    public function _before()
    {
        $this->fm = (new MuffinHelper())->init();
    }

    public function checkRegister(ApiTester $I)
    {
        $faker = \Faker\Factory::create();
        $email = $faker->email;
        $I->wantTo('register via the api');
        $I->sendPOST('users', [
            'email' => $email,
            'password' => $faker->password(6)
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['email' => $email]);
    }

    public function checkNoDoubeleEmailRegister(ApiTester $I)
    {
        $user = $this->fm->create(User::className());
        $I->wantTo('not register with an existing email');
        $I->sendPOST('users', [
            'email' => $user->email,
            'password' => 'testestast'
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['success' => false]);
    }
}

?>
