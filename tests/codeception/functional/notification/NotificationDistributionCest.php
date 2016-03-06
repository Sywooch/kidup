<?php
namespace codecept\functional\notification;

use booking\models\booking\Booking;
use codecept\_support\MuffinHelper;
use codecept\_support\MuffinTrait;
use codecept\_support\UserHelper;
use codecept\muffins\BookingMuffin;
use codecept\muffins\MessageMuffin;
use codecept\muffins\MobileDeviceMuffin;
use codecept\muffins\UserMuffin;
use functionalTester;
use League\FactoryMuffin\FactoryMuffin;
use message\models\message\Message;
use notification\components\NotificationDistributer;
use notification\models\base\MobileDevices;
use user\models\User;


/**
 * API test the notification endpoint.
 *
 * Class NotificationDistributionCest
 * @package codecept\functional\notification
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

    protected $currentToken = 0;
    protected $deviceId = 0;

    protected $notificationPath;

    public function _before()
    {
        $this->notificationPath = \Yii::$aliases['@runtime'].'/notification/';
        MobileDevices::deleteAll();
        User::deleteAll();
        $this->fm = (new MuffinHelper())->init();
        $this->emptyNotifications();
    }

    private function setPushEnabled(User $user, $enabled) {
        return $this->fm->create(MobileDeviceMuffin::class, [
            'user_id' => $user->id,
            'is_subscribed' => $enabled
        ]);
    }

    private function getMail() {
        $view = null;
        $params = null;
        $received = false;
        $viewpath = \Yii::$aliases['@runtime'] . '/notification/mail.view';
        if (file_exists($viewpath)) {
            $received = true;
            $view = file_get_contents($viewpath);
        }
        $paramspath = \Yii::$aliases['@runtime'] . '/notification/mail.params';
        if (file_exists($paramspath)) {
            $params = json_decode(file_get_contents($paramspath), true);
        }
        return [$received, $view, $params];
    }

    private function getPush() {
        $view = null;
        $params = null;
        $received = false;
        $viewpath = \Yii::$aliases['@runtime'] . '/notification/push.view';
        if (file_exists($viewpath)) {
            $received = true;
            $view = file_get_contents($viewpath);
        }
        $paramspath = \Yii::$aliases['@runtime'] . '/notification/push.params';
        if (file_exists($paramspath)) {
            $params = json_decode(file_get_contents($paramspath), true);
        }
        return [$received, $view, $params];
    }

    public function checkUnreadCount(functionalTester $I){
        /*
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
        });*/

    }

    private function emptyNotifications() {
        // Empty notifications
        if (strlen($this->notificationPath) == 0) {
            echo 'NOTIFICATION PATH NOT SET CORRECTLY!';
            exit();
        }
        $path = $this->notificationPath . '*';
        foreach (glob($path) as $file) {
            unlink($file);
        }
    }

    private function makeUser($email, $enablePush = true) {
        $user = $this->fm->create(UserMuffin::class, [
            'email' => $email
        ]);
        $mobile_device = $this->fm->create(MobileDeviceMuffin::class, [
            'user_id' => $user->id,
            'token' => $this->currentToken++,
            'device_id' => $this->deviceId++
        ]);
        $mobile_device->is_subscribed = $enablePush;
        $mobile_device->save();
        return $user;
    }

    // Mails only (no push)

    public function testUserWelcome(functionalTester $I) {
        $this->I = $I;

        $user = $this->makeUser('user@kidup.dk', true);

        $this->emptyNotifications();
        (new NotificationDistributer($user->id))->userWelcome($user);
        list($receivedMail, $mailView, $mailParams) = $this->getMail();
        list($receivedPush, $pushView, $pushParams) = $this->getPush();
        $I->assertTrue($receivedMail);
        $I->assertFalse($receivedPush);
    }

    public function testUserReconfirm(functionalTester $I) {
        $this->I = $I;

        $user = $this->makeUser('user@kidup.dk', true);

        $this->emptyNotifications();
        (new NotificationDistributer($user->id))->userReconfirm($user);
        list($receivedMail, $mailView, $mailParams) = $this->getMail();
        list($receivedPush, $pushView, $pushParams) = $this->getPush();
        $I->assertTrue($receivedMail);
        $I->assertFalse($receivedPush);
    }

    public function testUserRecovery(functionalTester $I) {
        $this->I = $I;

        $user = $this->makeUser('user@kidup.dk', true);

        $this->emptyNotifications();
        (new NotificationDistributer($user->id))->userRecovery($user, 'recovery-url');
        list($receivedMail, $mailView, $mailParams) = $this->getMail();
        list($receivedPush, $pushView, $pushParams) = $this->getPush();
        $I->assertTrue($receivedMail);
        $I->assertFalse($receivedPush);
    }

    public function testBookingPayoutOwner(functionalTester $I) {
        $this->I = $I;

        /** @var Booking $booking */
        $booking = $this->fm->create(BookingMuffin::class);

        $this->emptyNotifications();
        (new NotificationDistributer($booking->item->owner_id))->bookingPayoutOwner($booking);
        list($receivedMail, $mailView, $mailParams) = $this->getMail();
        list($receivedPush, $pushView, $pushParams) = $this->getPush();
        $I->assertTrue($receivedMail);
        $I->assertFalse($receivedPush);
    }

    // Push only (no mail)

    public function testConversationMessageReceived(functionalTester $I) {
        $this->I = $I;

        /** @var Message $message */
        $message = $this->fm->create(MessageMuffin::class);

        $this->setPushEnabled($message->conversation->target_user_id, true);
        $this->emptyNotifications();
        (new NotificationDistributer($message->conversation->target_user_id))->conversationMessageReceived($message);
        list($receivedMail, $mailView, $mailParams) = $this->getMail();
        list($receivedPush, $pushView, $pushParams) = $this->getPush();
        $I->assertFalse($receivedMail);
        $I->assertTrue($receivedPush);

        $this->setPushEnabled($message->conversation->targetUser, false);
        $this->emptyNotifications();
        (new NotificationDistributer($message->conversation->target_user_id))->conversationMessageReceived($message);
        list($receivedMail, $mailView, $mailParams) = $this->getMail();
        list($receivedPush, $pushView, $pushParams) = $this->getPush();
        $I->assertFalse($receivedMail);
        $I->assertFalse($receivedPush);
    }

    // Mails with fallbacks

    private function testBookingConfirmedOwner(functionalTester $I) {
        $this->I = $I;

        /** @var Booking $booking */
        $booking = $this->fm->create(BookingMuffin::class);

        $this->setPushEnabled($booking->item->owner, true);
        $this->emptyNotifications();
        (new NotificationDistributer($booking->item->owner_id))->bookingConfirmedOwner($booking);
        list($receivedMail, $mailView, $mailParams) = $this->getMail();
        list($receivedPush, $pushView, $pushParams) = $this->getPush();
        $I->assertFalse($receivedMail);
        $I->assertTrue($receivedPush);

        $this->setPushEnabled($booking->item->owner, false);
        $this->emptyNotifications();
        (new NotificationDistributer($booking->item->owner_id))->bookingConfirmedOwner($booking);
        list($receivedMail, $mailView, $mailParams) = $this->getMail();
        list($receivedPush, $pushView, $pushParams) = $this->getPush();
        $I->assertTrue($receivedMail);
        $I->assertFalse($receivedPush);
    }

}

