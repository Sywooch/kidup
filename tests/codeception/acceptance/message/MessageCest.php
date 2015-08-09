<?php
namespace app\tests\codeception\acceptance\message;

use AcceptanceTester;
use app\tests\codeception\_support\FixtureHelper;

/**
 * Acceptance test for the message module.
 *
 * Class MessageCest
 * @package app\tests\codeception\acceptance\message
 */
class MessageCest
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