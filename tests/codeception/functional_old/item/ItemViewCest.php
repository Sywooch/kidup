<?php
namespace app\tests\codeception\functional\item;

use app\tests\codeception\_support\UserHelper;
use functionalTester;
use app\tests\codeception\_support\FixtureHelper;

// todo add publishing test

/**
 * functional test for the item module.
 *
 * Class ItemCest
 * @package app\tests\codeception\functional\item
 */
class ItemViewCest
{

    public function checkItemView(functionalTester $I)
    {
        $I->amOnPage('/item/1');
        $I->see('This is pretty damn awesome!');
        $I->see('Test Item');
    }
}

?>