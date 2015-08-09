<?php
namespace app\tests\codeception\acceptance\images;

use AcceptanceTester;
use app\tests\codeception\_support\FixtureHelper;

/**
 * Acceptance test for the images module.
 *
 * Class ImagesCest
 * @package app\tests\codeception\acceptance\images
 */
class ImagesCest
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