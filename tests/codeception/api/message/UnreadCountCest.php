<?php
namespace codecept\api\message;

use codecept\_support\MuffinHelper;
use codecept\_support\UserHelper;
use codecept\muffins\MessageMuffin;
use codecept\muffins\UserMuffin;
use Codeception\Module\ApiHelper;
use League\FactoryMuffin\FactoryMuffin;
use ApiTester;
use message\models\message\Message;

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
        $I->wantTo('check I get the right amount of unreadcount');
        $user = $this->fm->create(UserMuffin::className());
        Message::deleteAll(['receiver_user_id'  => $user->id]);
        $m = $this->fm->create(MessageMuffin::class, [
            'receiver_user_id' => $user->id,
            'read_by_receiver' => 0
        ]);
        $I->sendGETAsUser($user, 'conversations/unread-count');
        $response = ApiHelper::checkJsonResponse($I);
        $I->assertEquals($response,"1");
    }
}

