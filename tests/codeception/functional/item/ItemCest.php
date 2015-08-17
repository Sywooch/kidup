<?php
namespace app\tests\codeception\functional\item;

use functionalTester;
use app\tests\codeception\_support\FixtureHelper;

// todo add publishing test

/**
 * functional test for the item module.
 *
 * Class ItemCest
 * @package app\tests\codeception\functional\item
 */
class ItemCest
{

    /**
     * Initialize the test.
     *
     * @param functionalTester $I
     */
    public function _before(functionalTester $I)
    {
        (new FixtureHelper)->fixtures();
    }

    public function checkItemView(functionalTester $I)
    {
        $I->amOnPage('/item/1');
        $I->see('This is pretty damn awesome!');
        $I->see('Test Item');
    }

}

?>