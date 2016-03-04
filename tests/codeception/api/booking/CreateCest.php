<?php
namespace codecept\api\booking;

use ApiTester;
use booking\models\Booking;
use Carbon\Carbon;
use codecept\_support\MuffinHelper;
use codecept\_support\UserHelper;
use codecept\muffins\ItemMuffin;
use codecept\muffins\UserMuffin;
use League\FactoryMuffin\FactoryMuffin;

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
        $this->user = $this->fm->create(UserMuffin::class);
    }

    /**
     * Check if I cannot make a booking as a guest.
     *
     * @param ApiTester $I
     */
    public function checkBookingGuest(ApiTester $I)
    {
        $item = $this->fm->create(ItemMuffin::className());
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
        $item = $this->fm->create(ItemMuffin::className());
        $item->is_available = true;
        $item->save();
        $I->wantTo("create a simple booking");
        $I->sendPOST('bookings?access-token=' . $accessToken, array_merge([
            'item_id' => $item->id,
            'time_from' => Carbon::now()->addDays(3)->timestamp,
            'time_to' => Carbon::now()->addDays(5)->timestamp,
            'message' => '',
            'payment_nonce' => 'fake-valid-nonce'
        ]));
        $I->seeResponseCodeIs(200);
        $res = json_decode($I->grabResponse(), true);
        $I->assertEquals($item->id, $res['item_id']);
        $I->assertEquals($this->user->id, $res['renter_id']);
        $I->assertEquals(Booking::PENDING, $res['status']);
        $I->assertEquals($item->getDailyPrice() * 2, $res['amount_item']);
    }

}