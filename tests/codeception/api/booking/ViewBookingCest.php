<?php

namespace codecept\api\booking;

use codecept\_support\MuffinHelper;
use codecept\_support\UserHelper;
use codecept\muffins\Booking;
use codecept\muffins\User;
use codecept\muffins\Item;
use League\FactoryMuffin\FactoryMuffin;
use ApiTester;
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
        $this->renter = $this->fm->create(User::class);
        $this->owner = $this->fm->create(User::class);
        $this->unknownUser = $this->fm->create(User::class);
        $this->item = $this->fm->create(Item::class, [
            'owner_id' => $this->owner->id
        ]);
        $this->booking = $this->fm->create(Booking::class,[
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
        $accessToken = UserHelper::apiLogin($this->unknownUser)['access-token'];
        $I->wantTo("check an outsider cant see a booking");
        $I->sendGET('bookings/'.$this->booking->id,[
            'access-token' => $accessToken
        ]);
        $I->seeResponseCodeIs(403);
    }

    public function ownerBooking(ApiTester $I) {
        $accessToken = UserHelper::apiLogin($this->owner)['access-token'];
        $I->wantTo("check an owner can see a booking");
        $I->sendGET('bookings/'.$this->booking->id,[
            'access-token' => $accessToken
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson(['item_id' => $this->item->id]);
        $I->seeResponseContainsJson(['id' => $this->booking->id]);
    }

    public function renterBooking(ApiTester $I) {
        $accessToken = UserHelper::apiLogin($this->renter)['access-token'];
        $I->wantTo("check an owner can see a booking");
        $I->sendGET('bookings/'.$this->booking->id,[
            'access-token' => $accessToken
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson(['item_id' => $this->item->id]);
        $I->seeResponseContainsJson(['id' => $this->booking->id]);
        $I->cantSeeResponseContains('amount_payout');
    }

}