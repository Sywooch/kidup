<?php
namespace app\tests\codeception\functional\booking;

use app\tests\codeception\_support\MuffinHelper;
use app\tests\codeception\muffins\Booking;
use functionalTester;
use Yii;

/**
 * Functional test for the booking module.
 *
 * Class BookingCest
 * @package app\tests\codeception\functional\booking
 */
class TestCest
{

    public function test($I){
        $fm = (new MuffinHelper())->init()->getFactory();
        $user = $fm->create(Booking::className());
    }

}

?>