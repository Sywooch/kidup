<?php
namespace app\tests\codeception\functional\home;

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

    /**
     * Initialize the test.
     *
     * @param FunctionalTester $I
     */
    public function _before(FunctionalTester $I)
    {
        (new FixtureHelper)->fixtures();
    }

    public function checkHomePage(functionalTester $I)
    {
        $I->wantTo('ensure that I can see the homepage');
        $I->amOnPage('/home');
        $I->canSee('KidUp is your online parent-to-parent marketplace.');
        $I->canSeeElement('input#query');
        $I->canSee('Stroller');
        $I->canSeeLink('Sign Up Now');
    }

    public function checkTranslations(functionalTester $I)
    {
        $I->wantTo('make sure translations work');
        $I->amOnPage('/home');
        $I->click('DA');
        $I->canSee('Hvordan bruger du KidUp?');
        $I->canSeeElement('input#query');
        $I->canSee('Barnevogn');
        $I->canSeeLink('Register dig nu');
    }
}
?>