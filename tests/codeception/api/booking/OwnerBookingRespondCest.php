<?php

namespace codecept\api\booking;

use booking\models\booking\Booking;
use booking\models\payin\Payin;
use codecept\_support\MuffinHelper;
use codecept\_support\UserHelper;
use codecept\muffins\BookingMuffin;
use codecept\muffins\ItemMuffin;
use codecept\muffins\PayoutMethodMuffin;
use codecept\muffins\UserMuffin;
use Codeception\Module\ApiHelper;
use League\FactoryMuffin\FactoryMuffin;
use ApiTester;
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

    public function _before()
    {
        $this->fm = (new MuffinHelper())->init();
        $this->owner = $this->fm->create(UserMuffin::class);
        $this->unknownUser = $this->fm->create(UserMuffin::class);
        $this->item = $this->fm->create(ItemMuffin::class, [
            'owner_id' => $this->owner->id
        ]);

        $payoutMethod = $this->fm->create(PayoutMethodMuffin::class, [
            'user_id' => $this->owner->id
        ]);
    }

    private function createBooking(\ApiTester $I, $authorize = true){
        $this->booking = $this->fm->create(BookingMuffin::class,[
            'item_id' => $this->item->id,
        ]);
        if($authorize){
            $this->booking->payin->authorize();
            $this->booking->status = Booking::PENDING;
            $this->booking->save();
        }
    }
    /**
     * Check if I can accept a booking
     *
     * @param ApiTester $I
     */
    public function canAcceptBooking(ApiTester $I) {
        $this->createBooking($I);
        $accessToken = UserHelper::apiLogin($this->owner)['access-token'];
        $I->wantTo("an owner can accept a booking");
        $this->booking->status = BookingMuffin::PENDING;
        $this->booking->save();
        $I->sendGET('bookings/accept',[
            'access-token' => $accessToken,
            'id' => $this->booking->id
        ]);
        ApiHelper::checkJsonResponse($I);
        $I->seeRecord(Booking::className(), [
            'id' => $this->booking->id,
            'status' => Booking::ACCEPTED
        ]);
    }

    /**
     * Check if I can retrieve expected costs of a booking.
     *
     * @param ApiTester $I
     */
    public function canDeclineBooking(ApiTester $I) {
        $this->createBooking($I);
        $accessToken = UserHelper::apiLogin($this->owner)['access-token'];
        $I->wantTo("as owner decline a booking");
        $this->booking->status = Booking::PENDING;
        $this->booking->save();
        $I->sendGET('bookings/decline',[
            'access-token' => $accessToken,
            'id' => $this->booking->id
        ]);
        ApiHelper::checkJsonResponse($I);
        $I->seeRecord(Booking::className(), [
            'id' => $this->booking->id,
            'status' => Booking::DECLINED
        ]);
    }

    /**
     * Check if I am restricted correctly
     *
     * @param ApiTester $I
     */
    public function cantAcceptOrDeclineAsUnknownUser(ApiTester $I) {
        $this->createBooking($I);
        $accessToken = UserHelper::apiLogin($this->unknownUser)['access-token'];
        $I->wantTo("have no access as unknown user");
        $I->sendGET('bookings/accept',[
            'access-token' => $accessToken,
            'id' => $this->booking->id
        ]);
        // @todo response codes should be equal here. not 403 / 400
        ApiHelper::checkJsonResponse($I, 403);
        $I->sendGET('bookings/decline',[
            'access-token' => $accessToken,
            'id' => $this->booking->id
        ]);
        ApiHelper::checkJsonResponse($I, 400);
    }

}