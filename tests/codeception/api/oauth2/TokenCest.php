<?php
namespace oauth2;
use \ApiTester;
use tests\muffins\OauthClient;
use tests\muffins\User;
use League\FactoryMuffin\FactoryMuffin;
use tests\_support\MuffinHelper;

class TokenCest
{
    /**
     * @var FactoryMuffin
     */
    protected $fm = null;
    private $user;
    private $client;

    public function _before()
    {
        $this->fm = (new MuffinHelper())->init();
        $this->user = $this->fm->create(User::class, [
            'password_hash' => \Yii::$app->security->generatePasswordHash('testtest')
        ]);

        $this->client = $this->fm->create(OauthClient::class);
    }

    public function _after()
    {
        User::deleteAll();
        OauthClient::deleteAll();
    }

    public function getToken(ApiTester $I)
    {
        $I->wantTo('get an api token');
        $this->sendPost($I, $this->user->email, 'testtest', $this->client);
        $I->seeResponseCodeIs(200);

        $I->seeResponseContains("refresh_token");
        $I->seeResponseContains("expires_in");
        $I->seeResponseContains('"token":');
    }

    public function dontGetTokenWithWrongClient(ApiTester $I)
    {
        $I->wantTo('get an api token');
        $this->client->client_id = 'dontexist1249012757125';
        $this->sendPost($I, $this->user->email, 'testtest', $this->client);
        $I->seeResponseCodeIs(400);
        $I->seeResponseContainsJson(['message' => 'Client not found.']);
    }

    public function dontGetTokenFromNonUser(ApiTester $I)
    {
        $faker = \Faker\Factory::create();
        $I->wantTo('get an api token');
        $this->sendPost($I, $faker->email, 'testst', $this->client);
        $I->seeResponseCodeIs(400);
    }

    protected function sendPost(ApiTester $I, $user, $pass, OauthClient $client){
        $I->sendPOST('oauth2/token', [
            'username' => $user,
            'password' => $pass,
            'client_id' => $client->client_id,
            'client_secret' => $client->client_secret
        ]);
        $I->seeResponseIsJson();
    }
}