<?php
namespace app\tests\codeception\acceptance\home;

use AcceptanceTester;
use app\tests\codeception\_support\FixtureHelper;

/**
 * Acceptance test for the home module.
 *
 * Class HomeCest
 * @package app\tests\codeception\acceptance\home
 */
class HomeCest
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