<?php
namespace app\tests\codeception\acceptance\review;

use AcceptanceTester;
use app\tests\codeception\_support\FixtureHelper;

/**
 * Acceptance test for the review module.
 *
 * Class ReviewCest
 * @package app\tests\codeception\acceptance\review
 */
class ReviewCest
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