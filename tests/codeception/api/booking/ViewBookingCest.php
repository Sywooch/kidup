<?php

namespace codecept\api\booking;

use ApiTester;
use codecept\_support\MuffinHelper;
use codecept\_support\UserHelper;
use codecept\muffins\BookingMuffin;
use codecept\muffins\ItemMuffin;
use codecept\muffins\UserMuffin;
use Codeception\Module\ApiHelper;
use League\FactoryMuffin\FactoryMuffin;

/**
 * API test for checking the costs of a booking.
 *
 * Class CostsBookingCest
 * @package codecept\api\booking
 */
class ViewBookingCest
{
    /**
     * @var FactoryMuffin
     */
    protected $fm = null;
    private $renter;
    private $owner;
    private $unknownUser;
    private $item;
    private $booking;

    public function _before()
    {
        $this->fm = (new MuffinHelper())->init();
        $this->renter = $this->fm->create(UserMuffin::class);
        $this->owner = $this->fm->create(UserMuffin::class);
        $this->unknownUser = $this->fm->create(UserMuffin::class);
        $this->item = $this->fm->create(ItemMuffin::class, [
            'owner_id' => $this->owner->id
        ]);
        $this->booking = $this->fm->create(BookingMuffin::class,[
            'item_id' => $this->item->id,
            'renter_id' => $this->renter->id
        ]);
    }

    /**
     * Check if I can retrieve expected costs of a booking.
     *
     * @param ApiTester $I
     */
    public function cantAccessOtherBooking(ApiTester $I) {
        $I->wantTo("check an outsider cant see a booking");
        $I->sendGETAsUser($this->unknownUser, 'bookings/'.$this->booking->id);
        ApiHelper::checkJsonResponse($I, 403);
    }

    public function ownerBooking(ApiTester $I) {
        $I->wantTo("check an owner can see a booking");
        $I->sendGET($this->owner, 'bookings/'.$this->booking->id);
        ApiHelper::checkJsonResponse($I);
        $I->seeResponseContainsJson(['item_id' => $this->item->id]);
        $I->seeResponseContainsJson(['id' => $this->booking->id]);
    }

    public function renterBooking(ApiTester $I) {
        $I->wantTo("check an owner can see a booking");
        $I->sendGET($this->renter, 'bookings/'.$this->booking->id);
        ApiHelper::checkJsonResponse($I);
        $I->seeResponseContainsJson(['item_id' => $this->item->id]);
        $I->seeResponseContainsJson(['id' => $this->booking->id]);
        $I->cantSeeResponseContains('amount_payout');
    }

}