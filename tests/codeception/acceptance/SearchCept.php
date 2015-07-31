<?php
/**
 * Acceptance test for the search module.
 *
 * @author kevin91nl
 */

/* @var $scenario Codeception\Scenario */

$I = new AcceptanceTester($scenario);
$I->amOnPage('/search/item/index');
$I->canSeeInTitle('test');