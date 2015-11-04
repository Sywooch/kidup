<?php
namespace tests\api\booking;

use app\tests\codeception\_support\MuffinHelper;
use app\tests\codeception\_support\UserHelper;
use app\tests\codeception\muffins\User;
use app\tests\codeception\muffins\Item;
use League\FactoryMuffin\FactoryMuffin;

/**
 * API test for checking the costs of a booking.
 *
 * Class CostsBookingCest
 * @package app\tests\codeception\api\booking
 */
class CostsCest
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
     * Check if I can retrieve expected costs of a booking.
     *
     * @param ApiTester $I
     */
    public function checkBookingCosts(ApiTester $I) {
        $accessToken = UserHelper::apiLogin($this->user)['access-token'];
        $item = $this->fm->create(Item::className());
        $item->is_available = true;
        $item->price_day = 1;
        $item->save();
        $I->wantTo("see the costs of a simple booking");
        $I->sendPOST('bookings/costs?access-token=' . $accessToken, array_merge([
            'item_id' => $item->id,
            'date_from' => date('d-m-Y', mktime(12, 0, 0, date('m'), date('N') + 3, date('Y'))),
            'date_to' => date('d-m-Y', mktime(12, 0, 0, date('m'), date('N') + 5, date('Y'))),
        ]));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $response = $I->grabResponse();
        $response = json_decode($response, true);
        $I->assertTrue(array_key_exists('tableData', $response));
    }

}