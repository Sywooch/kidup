<?php

namespace codecept\api\booking;

use ApiTester;
use Carbon\Carbon;
use codecept\_support\MuffinHelper;
use codecept\_support\UserHelper;
use codecept\muffins\ItemMuffin;
use codecept\muffins\UserMuffin;
use Codeception\Util\Debug;
use item\models\item\Item;
use League\FactoryMuffin\FactoryMuffin;

/**
 * API test for checking the costs of a booking.
 *
 * Class CostsBookingCest
 * @package codecept\api\booking
 */
class CostsCest
{
    /**
     * @var FactoryMuffin
     */
    protected $fm = null;
    public $user;
    public $item;

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
        $this->item->is_available = 1;
        $this->item->save();
    }

    /**
     * Check if I can retrieve expected costs of a booking.
     *
     * @param ApiTester $I
     */
    public function checkBookingCosts(ApiTester $I) {
        $accessToken = UserHelper::apiLogin($this->user)['access-token'];
        $I->wantTo("see the costs of a booking");
        $I->sendGET('bookings/costs',array_merge([
            'access-token' => $accessToken,
            'item_id' => $this->item->id,
            'time_from' =>  Carbon::now()->addDays(3)->timestamp,
            'time_to' =>  Carbon::now()->addDays(5)->timestamp,
        ]));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $response = $I->grabResponse();
        $response = json_decode($response, true);
        $I->assertTrue(array_key_exists('fee', $response));
        $I->assertTrue(array_key_exists('price', $response));
        $I->assertTrue(array_key_exists('total', $response));
        $I->assertContains("2", $response['price'][0]);
        $I->assertContains("fee", $response['fee'][0]);
        $I->assertContains("tal", $response['total'][0]);
        $I->seeResponseContains($this->item->getDailyPrice()."");

        $I->sendGET('bookings/costs',array_merge([
            'access-token' => $accessToken,
            'item_id' => $this->item->id,
            'time_from' =>  Carbon::now()->addDays(3)->timestamp,
            'time_to' =>  Carbon::now()->addDays(10)->timestamp,
        ]));
        $I->seeResponseCodeIs(200);
        $response = $I->grabResponse();
        $response = json_decode($response, true);
        $I->assertContains("1 week", $response['price'][0]);
        $I->seeResponseContains($this->item->getWeeklyPrice()."");

        $I->sendGET('bookings/costs',array_merge([
            'access-token' => $accessToken,
            'item_id' => $this->item->id,
            'time_from' =>  Carbon::now()->addDays(3)->timestamp,
            'time_to' =>  Carbon::now()->addDays(6)->addMonths(2)->timestamp,
        ]));
        $I->seeResponseCodeIs(200);
        $response = $I->grabResponse();
        $response = json_decode($response, true);
        $I->assertContains("month", $response['price'][0]);
        $I->seeResponseContains($this->item->getMonthlyPrice()."");
    }
}