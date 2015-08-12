<?php
namespace app\tests\codeception\functional\images;

use FunctionalTester;
use app\tests\codeception\_support\FixtureHelper;

/**
 * Functional test for the images module.
 *
 * Class ImagesCest
 * @package app\tests\codeception\functional\images
 */
class ImagesCest
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