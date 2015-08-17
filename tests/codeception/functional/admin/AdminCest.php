<?php
namespace app\tests\codeception\functional\admin;

use FunctionalTester;
use app\tests\codeception\_support\FixtureHelper;

/**
 * Functional test for the admin module.
 *
 * Class AdminCest
 * @package app\tests\codeception\functional\admin
 */
class AdminCest
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