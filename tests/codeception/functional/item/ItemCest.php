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
class ItemCest
{

    public function checkItemView(functionalTester $I)
    {
        $I->amOnPage('/item/1');
        $I->see('This is pretty damn awesome!');
        $I->see('Test Item');
    }

    public function checkCreateNewItem(FunctionalTester $I) {
        $I->wantTo('ensure that I can create a new item');
        UserHelper::login($I, 'simon@kidup.dk', 'testtest');
        $I->amOnPage('/item/list');
        $I->canSee('Create new');
        $I->click('Create new');
        $I->fillField('#create-item-name', 'New item name');
        // @todo
    }

}

?>