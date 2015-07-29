<?php
/* @var $scenario Codeception\Scenario */

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that KidUp is visible on the homepage');
$I->amOnPage('/');
$I->see('KidUp');
