<?php
namespace app\tests\codeception\functional\item;

use app\modules\booking\controllers\CronController;
use app\modules\booking\forms\Confirm;
use app\modules\booking\models\Booking;
use app\modules\booking\models\Payin;
use app\modules\message\models\Message;
use app\modules\review\models\Review;
use app\tests\codeception\_support\UserHelper;
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

    public function _before($event)
    {
        Booking::deleteAll();
        Review::deleteAll();
    }

    /**
     * Check whether it is possible to create a new booking (using a fake credit card).
     *
     * @param functionalTester $I
     */
    public function checkBookingProcess(FunctionalTester $I)
    {
        $dateFrom = date('d-m-Y', 1 * 24 * 60 * 60 + time());
        $dateTo = date('d-m-Y', 3 * 24 * 60 * 60 + time());

        $I->wantTo('ensure that I can make a booking');

        // load information
        $renterMessage = 'Because this is such an awesome item.';
        UserHelper::login($I, 'simon@kidup.dk', 'testtest');
        $renter = Yii::$app->getUser();
        $renterID = $renter->id;
        $item = Item::find()
            ->where([
                'id' => 2
            ])
            ->one()
        ;
        $ownerID = $item->owner_id;

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
            'receiver_user_id' => $ownerID,
            'sender_user_id' => $renterID
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
            'renter_id' => $renterID
        ]);

        // fake that a credit card payment was made
        $booking = Booking::find()
            ->where([
                'item_id' => 2,
                'renter_id' => $renterID
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
            'receiver_user_id' => $ownerID,
            'sender_user_id' => $renterID
        ]);

        $I->wantTo('ensure that I can accept a booking');

        UserHelper::logout($I);
        UserHelper::login($I, 'owner@kidup.dk', 'testtest');

        $booking = Booking::find()
            ->where([
                'item_id' => 2,
                'renter_id' => $renterID
            ])
            ->one()
        ;

        // execute the crons
        $cron = new CronController();
        $cron->minute();

        // accept the booking
        $I->amOnPage('/booking/' . $booking->id . '/request');
        $I->see('Accept booking');
        $I->see('Decline booking');
        $I->click('Accept booking');
        $I->see('Accepted');

        // there must be a record
        $I->seeRecord(Booking::class, [
            'item_id' => 2,
            'status' => Booking::ACCEPTED,
            'renter_id' => $renterID
        ]);

        $I->wantTo('ensure that I can write a review for the booking');

        // now set the date back into the past so it enabled us to write a review
        $dateFrom2 = time() - 3 * 24 * 60 * 60;
        $dateTo2 = time() - 1 * 24 * 60 * 60;
        $booking = Booking::find()
            ->where([
                'item_id' => 2,
                'renter_id' => $renterID
            ])
            ->one()
        ;
        $booking->time_from = $dateFrom2;
        $booking->time_to = $dateTo2;
        $booking->save();

        // go back to the renter
        UserHelper::logout($I);
        UserHelper::login($I, 'simon@kidup.dk', 'testtest');

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
        $I->amOnPage('/user/' . $ownerID);
        $I->see('Cool item (public message)');
    }

}

?>