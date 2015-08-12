<?php
namespace app\tests\codeception\functional\item;

use FunctionalTester;
use app\tests\codeception\_support\FixtureHelper;

/**
 * Functional test for the mail module.
 *
 * Class MailCest
 * @package app\tests\codeception\functional\mail
 */
class MailCest
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