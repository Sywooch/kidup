<?php
namespace app\tests\codeception\functional\booking;

use app\components\Event;
use app\modules\booking\controllers\CronController;
use app\modules\booking\controllers\DefaultController;
use app\modules\booking\forms\Confirm;
use app\modules\booking\widgets\CreateBooking;
use app\modules\message\models\Message;
use app\modules\review\models\Review;
use app\tests\codeception\_support\MuffinHelper;
use app\tests\codeception\_support\UserHelper;
use app\tests\codeception\_support\YiiHelper;
use app\tests\codeception\muffins\Booking;
use app\tests\codeception\muffins\Invoice;
use app\tests\codeception\muffins\Item;
use app\tests\codeception\muffins\Payin;
use app\tests\codeception\muffins\Payout;
use app\tests\codeception\muffins\Profile;
use app\tests\codeception\muffins\User;
use Codeception\Util\Debug;
use functionalTester;
use Faker\Factory as Faker;
use Yii;

/**
 * Functional test for the booking module.
 *
 * Class BookingCest
 * @package app\tests\codeception\functional\booking
 */
class BookingCest {

    protected static $fm = null;

    public function _before() {
        static::$fm = (new MuffinHelper())->init()->getFactory();
    }

    public function makeBooking(FunctionalTester $I) {
        $faker = Faker::create();
        $format = 'd-m-Y';

        // generate random dates with some additional noise
        $dateFrom = $faker->dateTimeBetween('+2 days', '+3 days')->getTimestamp() + $faker->numberBetween(10, 3600);
        $dateTo = $faker->dateTimeBetween('+5 days', '+8 days')->getTimestamp() + $faker->numberBetween(10, 3600);

        // calculate the number of days between these dates
        $numDays = floor($dateTo / 3600 / 24) - floor($dateFrom / 3600 / 24);

        // define the users and the item
        $renter = static::$fm->create(User::class);
        $owner = static::$fm->create(User::class);
        $item = static::$fm->create(Item::class);
        $item->owner_id = (int)$owner->id;
        $item->save();
        UserHelper::login($renter);

        $params = [
            'create-booking' => [
                'dateFrom' => date($format, $dateFrom),
                'dateTo' => date($format, $dateTo),
            ]
        ];

        // check the generated table
        $I->amOnPage('/item/' . $item->id . '?' . http_build_query($params));
        $I->canSee('Service fee');
        $I->canSee($numDays . ' days');
        $I->canSee('Request to Book');

        // go to the action page of the form
        $I->amOnPage('/item/' . $item->id . '?' . http_build_query($params) . '&_pjax=1');
        $I->canSee('Review and book');

        /*CreateBooking::widget([
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'currency_id' => 1,
            'item_id' => $item->id
        ])->run();
        $I->seeRecord(\app\models\base\Booking::class, [
            'renter_id' => $renter->id,
            'item_id' => $item->id,
        ]);*/
        // @todo
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