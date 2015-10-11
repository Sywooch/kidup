<?php
namespace app\tests\codeception\functional\home;

use app\tests\codeception\_support\MuffinHelper;
use FunctionalTester;
use app\tests\codeception\_support\FixtureHelper;

/**
 * Functional test for the home module.
 *
 * Class HomeCest
 * @package app\tests\codeception\functional\home
 */
class HomeCest
{

    public function checkHomePage(FunctionalTester $I)
    {
        $I->wantTo('ensure that I can see the homepage');
        $I->amOnPage('/home');
        $I->canSee('KidUp is your online');
        $I->canSeeElement('input#search-home-query');
        $I->canSee('Baby Necessities');
        $I->canSee('Register');
    }

    public function checkTranslations(FunctionalTester $I)
    {
        $I->wantTo('make sure translations work');
        $I->amOnPage('/home');
        $I->see('Danish');
        $I->click('Danish');
    }
}
?>