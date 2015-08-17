<?php
namespace app\tests\codeception\functional\item;

use app\modules\booking\models\Booking;
use app\modules\item\models\ItemHasMedia;
use app\modules\item\models\Media;
use app\tests\codeception\_support\UserHelper;
use functionalTester;
use app\tests\codeception\_support\FixtureHelper;
use app\modules\item\models\Item;

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
            ->where(['item_id' => 2])
            ->orderBy('created_at DESC')
            ->limit(1)
            ->one();
        Booking::deleteAll([
            'id' => $booking->id,
        ]);
    }

    public function checkBookingItem(FunctionalTester $I)
    {
        $dateFrom = date('d-m-Y', 1 * 24 * 60 * 60 + time());
        $dateTo = date('d-m-Y', 3 * 24 * 60 * 60 + time());

        $I->wantTo('ensure that I can make a booking');
        UserHelper::login($I, 'simon@kidup.dk', 'testtest');
        $I->amOnPage('/item/2');

        $I->amGoingTo('try to fill in the booking form');
        $I->fillField('#create-booking-datefrom', $dateFrom);
        $I->fillField('#create-booking-dateto', $dateTo);
        //$I->click('Start booking!', '#booking-navbar button[type=submit]');

        // @todo follow Yii redirect
    }
}

?>