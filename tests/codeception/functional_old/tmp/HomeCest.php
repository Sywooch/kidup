<?php
namespace app\tests\codeception\functional\home;

use app\tests\codeception\_support\FactoryHelper;
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
        (new FactoryHelper());
    }

}
?>