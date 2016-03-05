<?php

namespace codecept\api\booking;

use League\FactoryMuffin\FactoryMuffin;

/**
 * API test for checking the costs of a booking.
 *
 * Class CostsBookingCest
 * @package codecept\api\booking
 */
class OwnerBookingRespondCest
{
    /**
     * @var FactoryMuffin
     */
    protected $fm = null;
    private $owner;
    private $unknownUser;
    private $item;
    private $booking;

//    public function _before()
//    {
//        $this->fm = (new MuffinHelper())->init();
//        $this->owner = $this->fm->create(User::class);
//        $this->unknownUser = $this->fm->create(User::class);
//        $this->item = $this->fm->create(Item::class, [
//            'owner_id' => $this->owner->id
//        ]);
//
//        $payoutMethod = $this->fm->create(PayoutMethod::class, [
//            'user_id' => $this->owner->id
//        ]);
//    }
//
//    private function createBooking(ApiTester $I, $authorize = true){
//        $this->booking = $this->fm->create(Booking::class,[
//            'item_id' => $this->item->id,
//        ]);
//        $this->booking->payin->authorize();
//    }
//
//    /**
//     * Check if I can retrieve expected costs of a booking.
//     *
//     * @param ApiTester $I
//     */
//    public function canAcceptBooking(ApiTester $I) {
//        $this->createBooking($I);
//        $accessToken = UserHelper::apiLogin($this->owner)['access-token'];
//        $I->wantTo("an owner can accept a booking");
//        $this->booking->status = Booking::PENDING;
//        $this->booking->save();
//        $I->sendGET('bookings/accept',[
//            'access-token' => $accessToken,
//            'id' => $this->booking->id
//        ]);
//        $I->seeResponseCodeIs(200);
//        $I->seeRecord(Booking::className(), [
//            'id' => $this->booking->id,
//            'status' => Booking::ACCEPTED
//        ]);
//    }
//
//    /**
//     * Check if I can retrieve expected costs of a booking.
//     *
//     * @param ApiTester $I
//     */
//    public function canDeclineBooking(ApiTester $I) {
//        $accessToken = UserHelper::apiLogin($this->owner)['access-token'];
//        $I->wantTo("an owner can decline a booking");
//        $this->booking->status = Booking::PENDING;
//        $this->booking->save();
//        $I->sendGET('bookings/accept',[
//            'access-token' => $accessToken,
//            'id' => $this->booking->id
//        ]);
//        $I->seeResponseCodeIs(200);
//        $I->seeRecord(Booking::className(), [
//            'id' => $this->booking->id,
//            'status' => Booking::DECLINED
//        ]);
//    }
//
//    /**
//     * Check if I can retrieve expected costs of a booking.
//     *
//     * @param ApiTester $I
//     */
//    public function cantAcceptOrDeclineAsUnknownUser(ApiTester $I) {
//        $this->createBooking($I, false);
//        $accessToken = UserHelper::apiLogin($this->unknownUser)['access-token'];
//        $I->wantTo("have no access as unknown user");
//        $I->sendGET('bookings/accept',[
//            'access-token' => $accessToken,
//            'id' => $this->booking->id
//        ]);
//        $I->seeResponseCodeIs(403);
//        $I->sendGET('bookings/decline',[
//            'access-token' => $accessToken,
//            'id' => $this->booking->id
//        ]);
//        $I->seeResponseCodeIs(403);
//    }
}