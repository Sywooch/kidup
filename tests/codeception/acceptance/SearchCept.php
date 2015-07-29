<?php
/* @var $scenario Codeception\Scenario */

$I = new AcceptanceTester($scenario);
$I->amOnPage('/search/filter/query');
$I->click('.signup');
$I->seeInTitle('Thank you!');