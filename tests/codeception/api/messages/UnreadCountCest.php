<?php
namespace codecept\api\messaging;

use codecept\_support\MuffinHelper;
use codecept\_support\UserHelper;
use codecept\muffins\Message;
use codecept\muffins\User;
use League\FactoryMuffin\FactoryMuffin;
use ApiTester;

/**
 * API test the notification endpoint.
 *
 * Class SearchItemCest
 * @package codecept\api\item
 */
class UnreadCountCest
{

    /**
     * @var FactoryMuffin
     */
    protected $fm = null;

    public function _before()
    {
        $this->fm = (new MuffinHelper())->init();
    }

    public function checkUnreadCount(ApiTester $I){
//        $I->wantTo('check I get the right amount of unreadcount');
//        $user = $this->fm->create(User::className());
//        $accessToken = UserHelper::apiLogin($user);
////        Message::deleteAll();
//        $m = $this->fm->create(Message::class, [
////            'receiver_user_id' => $user->id,
////            'read_by_receiver' => 0
//        ]);
//        $I->sendGET('messages/unread-count', [
//            'access-token' => $accessToken,
//        ]);
//        $I->seeResponseCodeIs(200);
//        $I->assertEquals(1, $I->grabResponse());
    }
}

