<?php
namespace codecept\api\message;

use codecept\_support\MuffinHelper;
use codecept\_support\UserHelper;
use codecept\muffins\BookingMuffin;
use codecept\muffins\ConversationMuffin;
use codecept\muffins\ItemMuffin;
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
        $owner = $this->fm->create(UserMuffin::class);
        UserHelper::login($owner);
        $item = $this->fm->create(ItemMuffin::class, [
            'owner_id' => $owner->id
        ]);
        $booking = $this->fm->create(BookingMuffin::class, [
            'item_id' => $item->id
        ]);
        $c = $this->fm->create(ConversationMuffin::class, [
            'booking_id' => $booking->id
        ]);
        $m = $this->fm->create(MessageMuffin::class, [
            'conversation_id' => $c,
            'receiver_user_id' => $user->id,
            'read_by_receiver' => 0,
            'conversation_id' => $c->id
        ]);
        $I->sendGETAsUser($user, 'conversations/unread-count');
        $response = ApiHelper::checkJsonResponse($I);
        $I->assertEquals($response,"1");
    }
}

