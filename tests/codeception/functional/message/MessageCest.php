<?php
namespace app\tests\codeception\functional\message;

use FunctionalTester;
use app\tests\codeception\_support\FixtureHelper;

/**
 * Functional test for the message module.
 *
 * Class MessageCest
 * @package app\tests\codeception\functional\message
 */
class MessageCest
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