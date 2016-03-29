<?php

namespace codecept\api\booking;

use ApiTester;
use codecept\_support\MuffinHelper;
use Codeception\Module\ApiHelper;
use League\FactoryMuffin\FactoryMuffin;

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
        ApiHelper::checkJsonResponse($I);
    }

}