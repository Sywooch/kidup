<?php
namespace codecept\api\booking;

use Carbon\Carbon;
use codecept\_support\MuffinHelper;
use codecept\_support\UserHelper;
use codecept\muffins\User;
use codecept\muffins\Item;
use League\FactoryMuffin\FactoryMuffin;
use ApiTester;

/**
 * API test for creating a booking.
 *
 * Class CreateBookingCest
 * @package codecept\api\booking
 */
class CreateCest
{
    /**
     * @var FactoryMuffin
     */
    protected $fm = null;
    public $user;

    public function _before()
    {
        $this->fm = (new MuffinHelper())->init();
        \item\models\Item::deleteAll();
        $this->user = $this->fm->create(User::class);
    }

    /**
     * Check if I cannot make a booking as a guest.
     *
     * @param ApiTester $I
     */
    public function checkBookingGuest(ApiTester $I)
    {
        $item = $this->fm->create(Item::className());
        $I->wantTo("create a simple booking");
        $I->sendPOST('bookings', [
            'item_id' => $item->id,
            'date_from' => Carbon::now()->addDays(3)->timestamp,
            'date_to' => Carbon::now()->addDays(5)->timestamp
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(401);
    }

    /**
     * Create a simple booking.
     *
     * @param $I ApiTester The tester.
     */
    public function checkSimpleBooking(ApiTester $I)
    {
        $accessToken = UserHelper::apiLogin($this->user)['access-token'];
        $item = $this->fm->create(Item::className());
        $item->is_available = true;
        $item->save();
        $I->wantTo("create a simple booking");
        $I->sendPOST('bookings?access-token=' . $accessToken, array_merge([
            'item_id' => $item->id,
            'time_from' => Carbon::now()->addDays(3)->timestamp,
            'time_to' => Carbon::now()->addDays(5)->timestamp,
            'message' => '',
            'payment_nonce' => 'fake-nonce'
        ]));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"item_id":');
        $I->seeResponseContainsJson(['success' => true]);
    }

}