<?php
namespace app\tests\codeception\acceptance\mail;

use AcceptanceTester;
use app\tests\codeception\_support\FixtureHelper;

/**
 * Acceptance test for the mail module.
 *
 * Class MailCest
 * @package app\tests\codeception\acceptance\mail
 */
class MailCest
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