<?php
use tests\_support\MuffinHelper;
use tests\_support\UserHelper;
use tests\muffins\User;
use tests\muffins\Item;
use League\FactoryMuffin\FactoryMuffin;

/**
 * API test for checking the costs of a booking.
 *
 * Class CostsBookingCest
 * @package tests\api\booking
 */
class CostsBookingCest
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