<?php
namespace app\tests\codeception\acceptance\splash;

use AcceptanceTester;
use app\tests\codeception\_support\FixtureHelper;

/**
 * Acceptance test for the splash screen.
 *
 * Class SplashCest
 * @package app\tests\codeception\acceptance\splash
 */
class SplashCest
{

    /**
     * Initialize the test.
     *
     * @param AcceptanceTester $I
     */
    public function _before(AcceptanceTester $I)
    {
        (new FixtureHelper)->fixtures();
    }

    /**
     * Test whether the filter button is shown on small windows.
     *
     * @param AcceptanceTester $I
     */
    public function checkSignupFormExists(AcceptanceTester $I)
    {
        $I->wantTo('ensure that a sign-up form is visible');
        $I->amOnPage('/');
        $I->canSeeElement('#splash-signup-form');
    }

}

?>