<?php
namespace oauth2;
use \ApiTester;
use app\tests\codeception\muffins\User;
use League\FactoryMuffin\FactoryMuffin;
use app\tests\codeception\_support\MuffinHelper;

class TokenCest
{
    /**
     * @var FactoryMuffin
     */
    protected $fm = null;
    private $user;

    public function _before()
    {
        $this->fm = (new MuffinHelper())->init();
        $this->user = $this->fm->create(User::class, [
            'password_hash' => \Yii::$app->security->generatePasswordHash('testtest')
        ]);
    }

    // tests
    public function getToken(ApiTester $I)
    {
        $I->wantTo('get an api token');
        $this->sendPost($I, $this->user->email, 'testst');
        $I->seeResponseCodeIs(200);

        $I->seeResponseContains("refresh_token");
        $I->seeResponseContains("expires_in");
        $I->seeResponseContains('"token":');
    }

    public function dontGetTokenFromNonUser(ApiTester $I)
    {
        $faker = \Faker\Factory::create();
        $I->wantTo('get an api token');
        $this->sendPost($I, $faker->email, 'testst');
        $I->seeResponseCodeIs(400);
    }

    protected function sendPost(ApiTester $I, $user, $pass){
        $I->sendPOST('oauth2/token', [
            'username' => $user,
            'password' => $pass,
            'client_id' => 'testclient',
            'client_secret' => 'testpass'
        ]);
        $I->seeResponseIsJson();
    }
}