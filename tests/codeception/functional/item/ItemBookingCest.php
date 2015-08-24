<?php
namespace app\tests\codeception\functional\item;

use app\modules\booking\forms\Confirm;
use app\modules\booking\models\Booking;
use app\modules\booking\models\Payin;
use app\modules\item\models\ItemHasMedia;
use app\modules\item\models\Media;
use app\modules\message\models\Message;
use app\tests\codeception\_support\UserHelper;
use functionalTester;
use app\tests\codeception\_support\FixtureHelper;
use app\modules\item\models\Item;
use Yii;
use yii\debug\models\search\Profile;

// todo add publishing test

/**
 * functional test for the item module.
 *
 * Class ItemBookingCest
 * @package app\tests\codeception\functional\item
 */
class ItemBookingCest
{

    public function _after($event)
    {
        $booking = Booking::find()
            ->where([
                'item_id' => 2,
                'renter_id' => Yii::$app->getUser()->id
            ])
            ->one();
        Booking::deleteAll([
            'id' => $booking->id,
        ]);
    }

    /**
     * Check whether it is possible to create a new booking (using a fake credit card).
     *
     * @param functionalTester $I
     */
    public function checkCreateBooking(FunctionalTester $I)
    {
        $dateFrom = date('d-m-Y', 1 * 24 * 60 * 60 + time());
        $dateTo = date('d-m-Y', 3 * 24 * 60 * 60 + time());

        $I->wantTo('ensure that I can make a booking');
        $I->dontSeeRecord(Booking::class, [
            'item_id' => 2
        ]);
        $I->dontSeeRecord(Payin::class, [
            'status' => 'init'
        ]);
        UserHelper::login($I, 'simon@kidup.dk', 'testtest');
        $renter = Yii::$app->getUser();
        $renterID = $renter->id;
        $I->amOnPage('/item/2');

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
        $I->fillField('#confirm-booking-message', 'Because this is such an awesome item.');
        $I->checkOption('#confirm-booking-rules-1');
        $I->click('Book now');

        // now I log in as the product owner
        UserHelper::login($I, 'owner@kidup.dk', 'testtest');
        $I->seeRecord(Message::class, [
            'sender_user_id' => $renterID,
            'read_by_receiver' => false
        ]);
    }
}

?>