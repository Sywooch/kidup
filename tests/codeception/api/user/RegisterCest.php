<?php
use app\helpers\Event;
use codecept\_support\MuffinHelper;
use codecept\muffins\UserMuffin;
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
        Event::register(user\models\User::className(), user\models\User::EVENT_USER_CREATE_DONE, function ($event) use ($I) {
            echo 'hallo';
        });

        $faker = \Faker\Factory::create();
        $email = $faker->freeEmail;
        $I->wantTo('register via the api');
        $I->sendPOST('users', [
            'email' => $email,
            'password' => $faker->password(6),
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['email' => $email]);
    }

    public function checkNoDoubeleEmailRegister(ApiTester $I)
    {
        $faker = \Faker\Factory::create();
        $user = $this->fm->create(UserMuffin::className());
        $I->wantTo('not register with an existing email');
        $I->sendPOST('users', [
            'email' => $user->email,
            'password' => 'testestast',
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['success' => false]);
    }
}

?>
