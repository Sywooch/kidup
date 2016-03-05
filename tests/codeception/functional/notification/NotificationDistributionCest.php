<?php
namespace codecept\functional\notification;

use app\helpers\Event;
use booking\models\booking\Booking;
use booking\models\payin\Payin;
use codecept\_support\MuffinHelper;
use codecept\muffins\BookingMuffin;
use codecept\muffins\ConversationMuffin;
use codecept\muffins\UserMuffin;
use functionalTester;
use League\FactoryMuffin\FactoryMuffin;
use notification\components\NotificationDistributer;
use notification\models\base\MobileDevices;
use user\models\User;


/**
 * API test the notification endpoint.
 *
 * Class SearchItemCest
 * @package codecept\api\item
 */
class NotificationDistributionCest
{

    /**
     * @var FactoryMuffin
     */
    protected $fm = null;
    /**
     * @var FunctionalTester
     */
    protected $I;

    public function _before()
    {
        $this->fm = (new MuffinHelper())->init();
        MobileDevices::deleteAll();
    }

    public function checkUnreadCount(FunctionalTester $I){
        $this->I= $I;
        $user = $this->fm->create(UserMuffin::class);

        $this->checkNewEmail(function() use ($user){
            (new NotificationDistributer($user->id))->userWelcome($user);
        });

        $this->checkNewEmail(function() use ($user){
            (new NotificationDistributer($user->id))->userReconfirm($user);
        });

        $this->checkNewEmail(function() use ($user){
            (new NotificationDistributer($user->id))->userRecovery($user);
        });

        $booking = $this->fm->create(BookingMuffin::class);
        $this->checkNewEmail(function() use ($booking){
            (new NotificationDistributer($booking->renter_id))->bookingDeclinedRenter($booking);
        });

        $this->checkNewEmail(function() use ($booking){
            (new NotificationDistributer($booking->item->owner_id))->bookingPayoutOwner($booking);
        });

        $this->checkNewEmail(function() use ($booking){
            (new NotificationDistributer($booking->item->owner_id))->bookingRequestOwner($booking);
        });

        $this->checkNewEmail(function() use ($booking){
            (new NotificationDistributer($booking->renter_id))->bookingStartRenter($booking);
        });

        $this->checkNewEmail(function() use ($booking){
            (new NotificationDistributer($booking->item->owner_id))->bookingRequestOwner($booking);
        });

        $this->checkNewEmail(function() use ($booking){
            (new NotificationDistributer($booking->item->owner_id))->bookingConfirmedOwner($booking);
        });

        $this->checkNewEmail(function() use ($booking){
            (new NotificationDistributer($booking->renter_id))->bookingConfirmedRenter($booking);
        });

        $conversation = $this->fm->create(ConversationMuffin::class);
        $this->checkNewEmail(function() use ($conversation){
            (new NotificationDistributer($conversation->renter_id))->conversationMessageReceived($conversation);
        });
    }

    private function countNumberOfEmails(){
        return count(glob(\Yii::$aliases['@runtime'].'/mail/*.eml'));
    }

    private function checkNewEmail($callback){
        $c1 = $this->countNumberOfEmails();
        $callback();
        $this->I->assertEquals($c1+1, $this->countNumberOfEmails());
    }
}

