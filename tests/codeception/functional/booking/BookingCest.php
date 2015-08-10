<?php
namespace app\tests\codeception\functional\booking;

use FunctionalTester;
use app\tests\codeception\_support\FixtureHelper;

/**
 * Functional test for the booking module.
 *
 * Class BookingCest
 * @package app\tests\codeception\functional\booking
 */
class BookingCest
{

    /**
     * Initialize the test.
     *
     * @param FunctionalTester $I
     */
    public function _before(FunctionalTester $I)
    {
        (new FixtureHelper)->fixtures();
    }

}

?>