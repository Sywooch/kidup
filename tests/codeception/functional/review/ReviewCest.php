<?php
namespace app\tests\codeception\functional\review;

use FunctionalTester;
use app\tests\codeception\_support\FixtureHelper;

/**
 * Functional test for the review module.
 *
 * Class ReviewCest
 * @package app\tests\codeception\functional\review
 */
class ReviewCest
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