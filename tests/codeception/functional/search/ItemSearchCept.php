<?php
/**
 * A functional test for the item search.
 */

/* @var $scenario Codeception\Scenario */
use tests\codeception\_pages\ItemSearchPage;

$I = new FunctionalTester($scenario);
$I->wantTo('ensure item search page works');
$itemSearchPage = ItemSearchPage::openBy($I);
$itemSearchPage->search('abc');
?>