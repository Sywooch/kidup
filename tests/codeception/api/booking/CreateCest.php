<?php
namespace tests\api\booking;

use app\tests\codeception\_support\MuffinHelper;
use app\tests\codeception\_support\UserHelper;
use app\tests\codeception\muffins\User;
use app\tests\codeception\muffins\Item;
use League\FactoryMuffin\FactoryMuffin;

/**
 * API test for creating a booking.
 *
 * Class CreateBookingCest
 * @package app\tests\codeception\api\booking
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
    public function checkBookingGuest(ApiTester $I) {
        $item = $this->fm->create(Item::className());
        $I->wantTo("create a simple booking");
        $I->sendPOST('bookings', [
            'item_id' => $item->id,
            'date_from' => date('d-m-Y', mktime(12, 0, 0, date('m'), date('N') + 3, date('Y'))),
            'date_to' => date('d-m-Y', mktime(12, 0, 0, date('m'), date('N') + 5, date('Y')))
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
            'date_from' => date('d-m-Y', mktime(12, 0, 0, date('m'), date('N') + 3, date('Y'))),
            'date_to' => date('d-m-Y', mktime(12, 0, 0, date('m'), date('N') + 5, date('Y'))),
        ]));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $response = $I->grabResponse();
        $response = json_decode($response, true);
        $I->assertTrue(array_key_exists('success', $response));
        $I->assertEquals(true, $response['success']);
        $I->assertTrue(array_key_exists('booking_id', $response));
    }

}