<?php
namespace codecept\functional\home;

use codecept\_support\FixtureHelper;
use FunctionalTester;

/**
 * Functional test for the home module.
 *
 * Class HomeCest
 * @package codecept\functional\home
 */
class HomeCest
{

    public function checkFakeHomePage(FunctionalTester $I)
    {
        $I->wantTo('ensure that I can see the homepage');
        $I->amOnPage('/');
        $I->canSeeElement('img.applestore');
        $I->canSeeElement('iframe');
    }
}
?>