<?php
use codecept\_support\MuffinHelper;
use codecept\_support\UserHelper;
use codecept\muffins\User;
use League\FactoryMuffin\FactoryMuffin;

/**
 * functional test for the /me api point.
 *
 * Class RegisterCest
 * @package codecept\api\user
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
