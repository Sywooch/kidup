<?php
namespace app\tests\codeception\acceptance\booking;

use AcceptanceTester;
use app\tests\codeception\_support\FixtureHelper;

/**
 * Acceptance test for the booking module.
 *
 * Class BookingCest
 * @package app\tests\codeception\acceptance\booking
 */
class BookingCest
{

    /**
     * Initialize the test.
     *
     * @param AcceptanceTester $I
     */
    public function _before(AcceptanceTester $I)
    {
        (new FixtureHelper)->fixtures();
    }

}

?>