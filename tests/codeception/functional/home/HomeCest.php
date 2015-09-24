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
        $I->click('DA');
        $I->canSee('KidUp er din online');
        $I->canSeeElement('input#search-home-query');
        $I->canSee('Babyting');
        $I->canSee('Registrer');
    }

    public function checkTranslationChangeLinks(FunctionalTester $I) {
        $I->amOnPage('/home');

        // check state when clicked on EN
        $I->click('EN');
        $I->canSeeLink('EN');
        $I->canSeeLink('DA');

        // check state when clicked on DA
        $I->click('DA');
        $I->canSeeLink('EN');
        $I->canSeeLink('DA');
    }

}
?>