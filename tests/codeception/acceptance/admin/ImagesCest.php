<?php
namespace app\tests\codeception\acceptance\admin;

use AcceptanceTester;
use app\tests\codeception\_support\FixtureHelper;

/**
 * Acceptance test for the admin module.
 *
 * Class AdminCest
 * @package app\tests\codeception\acceptance\admin
 */
class AdminCest
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

}

?>