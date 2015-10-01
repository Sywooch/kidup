<?php
namespace app\tests\codeception\functional\booking;

use app\tests\codeception\_support\MuffinHelper;
use app\tests\codeception\_support\UserHelper;
use app\tests\codeception\muffins\Booking;
use app\tests\codeception\muffins\Item;
use app\tests\codeception\muffins\User;
use Codeception\Util\Debug;
use functionalTester;
use Faker\Factory as Faker;
use League\FactoryMuffin\FactoryMuffin;
use Yii;

/**
 * Functional test for the booking module.
 *
 * Class BookingCest
 * @package app\tests\codeception\functional\booking
 */
class BookingCest {

    /**
     * @var FactoryMuffin
     */
    protected $fm = null;

    public function _before() {
        $this->fm = (new MuffinHelper())->init();
    }

    public function makeBooking(FunctionalTester $I) {
        $faker = Faker::create();
        $format = 'd-m-Y';

        // generate random dates
        $dateFrom = $faker->dateTimeBetween('+2 days', '+3 days')->getTimestamp();
        $dateTo = $faker->dateTimeBetween('+5 days', '+8 days')->getTimestamp();

        // calculate the number of days between these dates
        $numDays = floor($dateTo / 3600 / 24) - floor($dateFrom / 3600 / 24);

        /**
         * @var Booking $booking
         */
        $booking = $this->fm->create(Booking::class);
        UserHelper::login($booking->renter);

        // check the generated table
        $I->amOnPage('/booking/' . $booking->id . '/confirm');
        $I->canSee('Secure Booking - Pay in 1 Minute');
        $I->canSee('Message to '.$booking->item->owner->profile->first_name);
    }

//    public function checkBookings($I){
//        $this->booking = $this->makeBooking($I);
//        $this->declineBooking($I, $this->booking);
//
//        $this->_before(null);
//
//        $this->booking = $this->makeBooking($I);
//        $this->acceptBooking($I, $this->booking);
//    }
//
//    /**
//     * Create a booking.
//     *
//     * @param FunctionalTester $I
//     * @return \app\modules\booking\models\Booking created booking
//     */
//    private function makeBooking($I)
//    {
//        // count the e-mails
//        $emailCountBefore = count(YiiHelper::listEmails());
//
//        $dateFrom = date('d-m-Y', 1 * 24 * 60 * 60 + time());
//        $dateTo = date('d-m-Y', 3 * 24 * 60 * 60 + time());
//
//        // load information
//        $renterMessage = 'Because this is such an awesome item.';
//        UserHelper::loginRenter();
//        $this->renter = Yii::$app->getUser();
//        $item = Item::findOne(2);
//        $this->owner = User::findOne($item->owner_id);
//
//        // check the database
//        $I->dontSeeRecord(Booking::class, [
//            'item_id' => 2
//        ]);
//        $I->dontSeeRecord(Review::class, [
//            'item_id' => 2
//        ]);
//        $I->dontSeeRecord(Payin::class, [
//            'status' => 'init'
//        ]);
//        $I->dontSeeRecord(Message::class, [
//            'receiver_user_id' => $this->owner->id,
//            'sender_user_id' => $this->renter->id
//        ]);
//
//        // go to the initial page to make a booking
//        $I->amOnPage('/item/2');
//
//        // fill in the booking form
//        $I->amGoingTo('try to fill in the booking form');
//        $I->fillField('#create-booking-datefrom', $dateFrom);
//        $I->fillField('#create-booking-dateto', $dateTo);
//        // tests only have to click once, frontend users twice
//        $I->click('Request to Book');
//        $I->seeRecord(Booking::class, [
//            'item_id' => 2,
//            'status' => Booking::AWAITING_PAYMENT,
//            'renter_id' => $this->renter->id
//        ]);
//
//        // fake that a credit card payment was made
//        $booking = Booking::find()
//            ->where([
//                'item_id' => 2,
//                'renter_id' => $this->renter->id
//            ])
//            ->one();
//        $I->amOnPage('/booking/' . $booking->id . '/confirm');
//        $I->see('Review and book');
//        $I->fillField('#confirm-booking-message', $renterMessage);
//        $I->checkOption('#confirm-booking-rules');
//        $I->click('Book now');
//        $I->amOnPage('/booking/' . $booking->id);
//
//        // refresh data
//        $booking = Booking::findOne($booking->id);
//        $this->booking = $booking;
//
//        $I->seeRecord(Payin::class, [
//            'id' => $booking->payin_id
//        ]);
//
//        // check whether there was sent a message to the product owner
//        $I->seeRecord(Message::class, [
//            'receiver_user_id' => $this->owner->id,
//            'sender_user_id' => $this->renter->id
//        ]);
//
//        // there should be some e-mails in the queue
//        $emailCountAfter = count(YiiHelper::listEmails());
//        $emailDeltaCount = $emailCountAfter - $emailCountBefore;
//        $I->assertTrue($emailDeltaCount == 1);
//        return $booking;
//    }
//
//    /**
//     * Decline a booking.
//     *
//     * @param $I FunctionalTester
//     * @param \app\modules\booking\models\Booking Booking to decline.
//     */
//    private function declineBooking($I, $booking)
//    {
//        UserHelper::loginOwner();
//
//        // execute the crons
//        $cron = new CronController();
//        $cron->minute();
//
//        // decline the booking
//        $I->amOnPage('/booking/' . $booking->id . '/request');
//        $I->see('Decline booking');
//        $I->click('Decline booking');
//        $I->see('Declined');
//
//        // refresh data
//        $booking = Booking::findOne($booking->id);
//        $this->booking = $booking;
//
//        // there must be a record
//        $I->assertEquals(Booking::DECLINED, $booking->status);
//    }
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