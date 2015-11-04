<?php
namespace tests\functional\booking;

use tests\_support\MuffinHelper;
use tests\_support\UserHelper;
use tests\_support\YiiHelper;
use tests\functional\FunctionalTest;
use tests\muffins\Booking;
use tests\muffins\Item;
use tests\muffins\User;
use booking\models\Payin;
use Codeception\Util\Debug;
use functionalTester;
use Faker\Factory as Faker;
use item\controllers\CronController;
use League\FactoryMuffin\FactoryMuffin;
use message\models\Message;
use Yii;

/**
 * Functional test for the booking module.
 *
 * Class BookingCest
 * @package tests\functional\booking
 */
class BookingRespondCest{

    /**
     * @var FactoryMuffin
     */
    protected $fm = null;

    public function _before() {
        $this->fm = (new MuffinHelper())->init();
    }

    /**
     * Decline a booking.
     *
     * @param $I FunctionalTester
     */
    public function declineBooking(FunctionalTester $I)
    {
//        $booking = $this->fm->create(Booking::class,[
//            'status' => \booking\models\Booking::PENDING
//        ]);
//        UserHelper::login($booking->item->owner);
//
//        // execute the crons
//        $cron = new \booking\controllers\CronController();
//        $cron->minute();
//
//        // decline the booking
//        $I->amOnPage('/booking/' . $booking->id . '/request');
//        $I->see('Decline booking');
//        $I->click('Decline booking');
//        $I->see('Declined');
//
//        $booking->refresh();
//
//        // there must be a record
//        $I->assertEquals(Booking::DECLINED, $booking->status);
    }
//
//    /**
//     * Accept a booking.
//     *
//     * @param $I FunctionalTester
//     * @param \app\modules\booking\models\Booking Booking to accept.
//     */
//    private function acceptBooking($I, $booking)
//    {
//        UserHelper::loginOwner();
//
//        // execute the crons
//        $cron = new CronController();
//        $cron->minute();
//
//        // accept the booking
//        $I->amOnPage('/booking/' . $booking->id . '/request');
//        $I->see('Accept booking');
//        $I->click('Accept booking');
//        $I->see('Accepted');
//
//        // refresh data
//        $booking = Booking::findOne($booking->id);
//        $this->booking = $booking;
//
//        // there must be a record
//        $I->assertEquals(Booking::ACCEPTED, $booking->status);
//    }


}

?>