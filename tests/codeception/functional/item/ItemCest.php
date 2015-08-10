<?php
namespace app\tests\codeception\functional\item;

use AcceptanceTester;
use app\tests\codeception\_support\FixtureHelper;

/**
 * Acceptance test for the item module.
 *
 * Class ItemCest
 * @package app\tests\codeception\acceptance\item
 */
class ItemCest
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