<?php
namespace app\tests\codeception\functional\item;

use app\components\Event;
use app\modules\booking\controllers\CronController;
use app\modules\booking\forms\Confirm;
use app\modules\booking\models\Booking;
use app\modules\booking\models\Payin;
use app\modules\message\models\Message;
use app\modules\review\models\Review;
use app\modules\user\models\User;
use app\tests\codeception\_support\UserHelper;
use app\tests\codeception\_support\YiiHelper;
use functionalTester;
use app\modules\item\models\Item;
use Yii;

// todo add publishing test

/**
 * functional test for the item module.
 *
 * Class ItemBookingCest
 * @package app\tests\codeception\functional\item
 */
class ItemBookingCest
{
    
    private $renter;
    private $owner;

    public function _before($event)
    {
        Payin::deleteAll();
        Message::deleteAll();
        Booking::deleteAll();
        Review::deleteAll();
    }

    /**
     * It should not be possible to write a review for a booking which is not yours.
     *
     * @param FunctionalTester $I
     */
    public function checkCreateBookingCreateReviewAuthorizationError(FunctionalTester $I)
    {
        $I->wantTo('ensure that it is not possible to write a review for a booking which is not yours');
        $booking = $this->createBooking($I);
        $this->acceptBooking($I, $booking);

        $this->loginOwner($I);
        $I->amOnPage('/review/create/' . $booking->id);
        $I->dontSeeElement('form #owner-review-public');
        $this->logout($I);
    }

    /**
     * It should not be possible to write a review before the renting period is over.
     *
     * @param FunctionalTester $I
     */
    public function checkCreateBookingCreateReviewPeriodError(FunctionalTester $I)
    {
        $I->wantTo('ensure that it is not possible to write a review before the renting period is over');
        $booking = $this->createBooking($I);
        $this->acceptBooking($I, $booking);

        $this->loginRenter($I);
        $I->amOnPage('/review/create/' . $booking->id);
        $I->dontSeeElement('form #owner-review-public');
        $this->logout($I);
    }

    /**
     * Check whether it is possible to create a new booking (using a fake credit card)
     * and review it afterwards.
     *
     * @param FunctionalTester $I
     */
    public function checkCreateBookingCreateReview(FunctionalTester $I)
    {
        $I->wantTo('ensure that I can make a booking and review it afterwards');
        $booking = $this->createBooking($I);
        $this->acceptBooking($I, $booking);
        $this->setRentingPeriodInPast($I, $booking);
        $this->writeReview($I, $booking);
    }

    /**
     * Check whether it is possible to create a new booking and let the owner decline.
     *
     * @param FunctionalTester $I
     */
    public function checkCreateBookingDeclineBooking(FunctionalTester $I) {
        $I->wantTo('ensure that I can make a booking and let the owner decline it');
        $booking = $this->createBooking($I);
        $this->declineBooking($I, $booking);
    }

    /**
     * Create a booking.
     *
     * @param FunctionalTester $I
     * @return app\modules\booking\models\Booking created booking
     */
    private function createBooking($I) {
        // count the e-mails
        $emailCountBefore = count(YiiHelper::listEmails());

        $dateFrom = date('d-m-Y', 1 * 24 * 60 * 60 + time());
        $dateTo = date('d-m-Y', 3 * 24 * 60 * 60 + time());

        // load information
        $renterMessage = 'Because this is such an awesome item.';
        $this->loginRenter($I);
        $this->renter = Yii::$app->getUser();
        $item = Item::find()
            ->where([
                'id' => 2
            ])
            ->one()
        ;
        $this->owner = User::find()
            ->where([
                'id' => $item->owner_id
            ])
            ->one()
        ;

        // check the database
        $I->dontSeeRecord(Booking::class, [
            'item_id' => 2
        ]);
        $I->dontSeeRecord(Review::class, [
            'item_id' => 2
        ]);
        $I->dontSeeRecord(Payin::class, [
            'status' => 'init'
        ]);
        $I->dontSeeRecord(Booking::class, [
            'item_id' => 2,
        ]);
        $I->dontSeeRecord(Message::class, [
            'receiver_user_id' => $this->owner->id,
            'sender_user_id' => $this->renter->id
        ]);

        // go to the initial page to make a booking
        $I->amOnPage('/item/2');

        // fill in the booking form
        $I->amGoingTo('try to fill in the booking form');
        $I->fillField('#create-booking-datefrom', $dateFrom);
        $I->fillField('#create-booking-dateto', $dateTo);
        $I->click('Start booking!', '#booking-navbar button[type=submit]');
        $I->seeRecord(Booking::class, [
            'item_id' => 2,
            'status' => Booking::AWAITING_PAYMENT,
            'renter_id' => $this->renter->id
        ]);

        // fake that a credit card payment was made
        $booking = Booking::find()
            ->where([
                'item_id' => 2,
                'renter_id' => $this->renter->id
            ])
            ->one();
        Confirm::createPayin($booking, 'fake-valid-nonce');
        $I->seeRecord(Payin::class, [
            'status' => Payin::STATUS_INIT,
        ]);
        $I->amOnPage('/booking/' . $booking->id . '/confirm');

        // check the booking page again and make the booking
        $I->see('Credit card accepted');
        $I->fillField('#confirm-booking-message', $renterMessage);
        $I->checkOption('#confirm-booking-rules-1');
        $I->click('Book now');

        // check whether there was sent a message to the product owner
        $I->seeRecord(Message::class, [
            'receiver_user_id' => $this->owner->id,
            'sender_user_id' => $this->renter->id
        ]);
        $this->logout($I);

        $booking = Booking::find()
            ->where([
                'item_id' => 2,
                'renter_id' => $this->renter->id
            ])
            ->one()
        ;

        // there should be some e-mails in the queue
        $emailCountAfter = count(YiiHelper::listEmails());
        $emailDeltaCount = $emailCountAfter - $emailCountBefore;
        $I->assertTrue($emailDeltaCount == 1);
        return $booking;
    }

    /**
     * Decline a booking.
     *
     * @param $I FunctionalTester
     * @param app\modules\booking\models\Booking Booking to decline.
     */
    private function declineBooking($I, $booking) {
        $this->loginOwner($I);

        // execute the crons
        $cron = new CronController();
        $cron->minute();

        // decline the booking
        $I->amOnPage('/booking/' . $booking->id . '/request');
        $I->see('Decline booking');
        $I->click('Decline booking');
        $I->see('Declined');

        // there must be a record
        $I->seeRecord(Booking::class, [
            'item_id' => 2,
            'status' => Booking::DECLINED,
            'renter_id' => $this->renter->id
        ]);
        $this->logout($I);
    }

    /**
     * Accept a booking.
     *
     * @param $I FunctionalTester
     * @param app\modules\booking\models\Booking Booking to accept.
     */
    private function acceptBooking($I, $booking) {
        $this->loginOwner($I);

        $booking = Booking::find()
            ->where([
                'item_id' => 2,
                'renter_id' => $this->renter->id
            ])
            ->one()
        ;

        // execute the crons
        $cron = new CronController();
        $cron->minute();

        // accept the booking
        $I->amOnPage('/booking/' . $booking->id . '/request');
        $I->see('Accept booking');
        $I->click('Accept booking');
        $I->see('Accepted');

        // there must be a record
        $I->seeRecord(Booking::class, [
            'item_id' => 2,
            'status' => Booking::ACCEPTED,
            'renter_id' => $this->renter->id
        ]);
        $this->logout($I);
    }

    /**
     * Set the renting period of a booking to the past.
     *
     * @param $I FunctionalTester
     * @param app\modules\booking\models\Booking Booking to modify
     */
    private function setRentingPeriodInPast($I, $booking) {
        // now set the date back into the past so it enabled us to write a review
        $dateFrom2 = time() - 3 * 24 * 60 * 60;
        $dateTo2 = time() - 1 * 24 * 60 * 60;
        $booking->time_from = $dateFrom2;
        $booking->time_to = $dateTo2;
        $booking->save();
    }

    /**
     * Write a review.
     *
     * @param $I FunctionalTester
     * @param app\modules\booking\models\Booking Booking to write the review for
     */
    private function writeReview($I, $booking) {
        // go back to the renter
        $this->loginRenter($I);

        $I->amOnPage('/review/create/' . $booking->id);

        // fill in the review form
        $I->seeElement('form #owner-review-public');
        $I->fillField('form #owner-review-public', 'Cool item (public message)');
        $I->fillField('form #owner-review-private', 'Cool item (private message)');
        $I->fillField('form #owner-review-experience', '3');
        $I->fillField('form #owner-review-communication', '3');
        $I->fillField('form #owner-review-exchange', '3');
        $I->fillField('form #owner-review-adaccuracy', '3');
        $I->fillField('form #owner-review-kidupprivate', ' Cool item (KidUp message)');
        $I->click('Submit Review');

        // check whether the review exists
        $I->amOnPage('/user/' . $this->owner->id);
        $I->see('Cool item (public message)');
        $this->logout($I);
    }

    /**
     * Login as an owner.
     *
     * @param $I FunctionalTester
     */
    private function loginOwner($I) {
        UserHelper::login($I, 'owner@kidup.dk', 'testtest');
    }

    /**
     * Login as a renter.
     *
     * @param $I FunctionalTester
     */
    private function loginRenter($I) {
        UserHelper::login($I, 'simon@kidup.dk', 'testtest');
    }

    /**
     * Login as an outsider.
     *
     * @param $I FunctionalTester
     */
    private function loginOutsider($I) {
        UserHelper::login($I, 'ihavenolocation@kidup.dk', 'testtest');
    }

    /**
     * Logout.
     *
     * @param $I FunctionalTester
     */
    private function logout($I) {
        UserHelper::logout($I);
    }

}

?>