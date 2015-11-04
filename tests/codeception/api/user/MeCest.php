<?php
use app\tests\codeception\_support\MuffinHelper;
use app\tests\codeception\muffins\User;
use League\FactoryMuffin\FactoryMuffin;
use app\tests\codeception\_support\UserHelper;
/**
 * functional test for the /me api point.
 *
 * Class RegisterCest
 * @package app\tests\codeception\api\user
 */
class MeCest
{
    /**
     * @var FactoryMuffin
     */
    protected $fm = null;
    private $user;

    public function _before()
    {
        $this->fm = (new MuffinHelper())->init();
        $this->user = $this->fm->create(User::class);
    }

    public function getMe(ApiTester $I)
    {
        $I->sendGET('users/me', UserHelper::apiLogin($this->user));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['id' => $this->user->id, 'email' => $this->user->email]);
    }
}

?>