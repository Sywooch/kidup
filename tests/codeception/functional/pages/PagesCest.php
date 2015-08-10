<?php
namespace app\tests\codeception\functional\pages;

use FunctionalTester;
use app\tests\codeception\_support\FixtureHelper;

/**
 * Functional test for the pages module.
 *
 * Class PagesCest
 * @package app\tests\codeception\functional\pages
 */
class PagesCest
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