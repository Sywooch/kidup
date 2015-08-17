<?php
namespace app\tests\codeception\functional\splash;

use FunctionalTester;
use app\tests\codeception\_support\FixtureHelper;

/**
 * Functional test for the splash screen.
 *
 * Class SplashCest
 * @package app\tests\codeception\functional\splash
 */
class SplashCest
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

}

?>