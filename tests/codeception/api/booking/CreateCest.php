<?php
namespace codecept\api\booking;

use ApiTester;
use booking\models\booking\Booking;
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
        \item\models\item\Item::deleteAll();
        $this->user = $this->fm->create(UserMuffin::class);

        $owner = $this->fm->create(UserMuffin::className());
        UserHelper::login($owner);
        /** @var Item item */
        $this->item = $this->fm->create(ItemMuffin::className(), [
            'owner_id' => $owner->id
        ]);
    }

    /**
     * Check if I cannot make a booking as a guest.
     *
     * @param ApiTester $I
     */
    public function checkBookingGuest(ApiTester $I)
    {
        $I->wantTo("create a simple booking");
        $I->sendPOST('bookings', [
            'item_id' => $this->item->id,
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
        $this->item->is_available = 1;
        $this->item->save();
        $I->wantTo("create a simple booking");
        $I->sendPOST('bookings?access-token=' . $accessToken, array_merge([
            'item_id' => $this->item->id,
            'time_from' => Carbon::now()->addDays(3)->timestamp,
            'time_to' => Carbon::now()->addDays(5)->timestamp,
            'message' => '',
            'payment_nonce' => 'fake-valid-nonce'
        ]));
        $I->seeResponseCodeIs(200);
        $res = json_decode($I->grabResponse(), true);
        $I->assertEquals($this->item->id, $res['item_id']);
        $I->assertEquals($this->user->id, $res['renter_id']);
        $I->assertEquals(Booking::PENDING, $res['status']);
        $I->assertEquals($this->item->getDailyPrice() * 2, $res['amount_item']);
    }

}