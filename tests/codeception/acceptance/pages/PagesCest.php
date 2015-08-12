<?php
namespace app\tests\codeception\acceptance\pages;

use AcceptanceTester;
use app\tests\codeception\_support\FixtureHelper;

/**
 * Acceptance test for the pages module.
 *
 * Class PagesCest
 * @package app\tests\codeception\acceptance\pages
 */
class PagesCest
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