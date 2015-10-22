<?php
namespace app\tests\codeception\functional\booking;

use app\tests\codeception\_support\MuffinHelper;
use app\tests\codeception\_support\UserHelper;
use app\tests\codeception\_support\YiiHelper;
use app\tests\codeception\functional\FunctionalTest;
use app\tests\codeception\muffins\Booking;
use app\tests\codeception\muffins\Item;
use app\tests\codeception\muffins\User;
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
 * @package app\tests\codeception\functional\booking
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