<?php

namespace codecept\api\booking;

use codecept\_support\MuffinHelper;
use codecept\_support\UserHelper;
use codecept\muffins\User;
use codecept\muffins\Item;
use League\FactoryMuffin\FactoryMuffin;
use ApiTester;
/**
 * API test for checking the costs of a booking.
 *
 * Class CostsBookingCest
 * @package codecept\api\booking
 */
class PaymentToken
{
    /**
     * @var FactoryMuffin
     */
    protected $fm = null;

    public function _before()
    {
        $this->fm = (new MuffinHelper())->init();
    }

    /**
     * Check if I can retrieve expected costs of a booking.
     *
     * @param ApiTester $I
     */
    public function checkPaymentToken(ApiTester $I) {
        $I->wantTo("get a payment token");
        $I->sendGET('bookings/payment-token');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('"token":');
    }

}