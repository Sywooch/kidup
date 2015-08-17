<?php
namespace app\tests\codeception\acceptance\item;

use AcceptanceTester;
use app\tests\codeception\_support\FixtureHelper;
use app\tests\codeception\_support\UserHelper;

/**
 * Acceptance test for the item module.
 *
 * Class ItemCest
 * @package app\tests\codeception\acceptance\item
 */
class ItemCest
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

    public function testBooking(AcceptanceTester $I) {
        $from = date('d-m-Y', time() + 24 * 60 * 60);
        $to = date('d-m-Y', time() + 5 * 24 * 60 * 60);
        UserHelper::loginAcceptance('simon@kidup.dk', 'testtest');
        $I->amOnPage('/item/1');
        $I->fillField('#create-booking-datefrom', $from);
        $I->fillField('#create-booking-dateto', $to);
        $I->click('#booking-navbar button[type=submit]');
        $I->wait(2);
        $I->canSeeInCurrentUrl('booking');
        $I->canSeeInCurrentUrl('confirm');
    }

}

?>