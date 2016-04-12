<?php
namespace codecept\api\notification;

use admin\models\I18nMessage;
use admin\models\I18nSource;
use ApiTester;
use booking\models\booking\Booking;
use codecept\_support\MuffinHelper;
use codecept\_support\NotificationHelper;
use codecept\_support\UserHelper;
use codecept\muffins\BookingMuffin;
use codecept\muffins\ConversationMuffin;
use codecept\muffins\I18nMessageMuffin;
use codecept\muffins\I18nSourceMuffin;
use codecept\muffins\ItemMuffin;
use codecept\muffins\MessageMuffin;
use codecept\muffins\MobileDeviceMuffin;
use codecept\muffins\UserMuffin;
use League\FactoryMuffin\FactoryMuffin;
use notification\components\NotificationDistributer;
use notification\models\base\MobileDevices;
use notification\models\NotificationMailLog;
use notification\models\NotificationPushLog;
use user\models\user\User;


/**
 * API test the notification endpoint.
 *
 * Class NotificationDistributionCest
 * @package codecept\api\notification
 */
class NotificationDistributionCest
{

    /**
     * @var FactoryMuffin
     */
    protected $fm = null;
    /**
     * @var ApiTester
     */
    protected $I;

    protected $currentToken = 0;
    protected $deviceId = 0;

    protected $notificationPath;

    public function _before()
    {
        MobileDevices::deleteAll();
        User::deleteAll();
        $this->fm = (new MuffinHelper())->init();
        NotificationHelper::emptyNotifications();
    }

    private function setPushEnabled(User $user, $enabled)
    {
        MobileDevices::deleteAll(['user_id' => $user->id]);
        return $this->fm->create(MobileDeviceMuffin::class, [
            'user_id' => $user->id,
            'is_subscribed' => $enabled
        ]);
    }

    private function makeUser($email, $enablePush = true)
    {
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

    public function testUserWelcome(ApiTester $I)
    {
        $this->I = $I;
        NotificationHelper::emptyNotifications();

        // Trigger user create event
        $faker = \Faker\Factory::create();
        $email = $faker->freeEmail;
        $I->sendPOST('users', [
            'email' => $email,
            'password' => $faker->password(6),
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
        ]);

        list($receivedMail, $mailView, $mailParams) = NotificationHelper::getMail();
        list($receivedPush, $pushView, $pushParams) = NotificationHelper::getPush();
        $I->assertTrue($receivedMail);
        $I->assertFalse($receivedPush);
    }

    public function testUserReconfirm(ApiTester $I)
    {
        $this->I = $I;

        $user = $this->makeUser('user@kidup.dk', true);

        NotificationHelper::emptyNotifications();
        (new NotificationDistributer($user->id))->userReconfirm($user);
        list($receivedMail, $mailView, $mailParams) = NotificationHelper::getMail();
        list($receivedPush, $pushView, $pushParams) = NotificationHelper::getPush();
        $I->assertTrue($receivedMail);
        $I->assertFalse($receivedPush);
    }

    public function testUserRecovery(ApiTester $I)
    {
        $this->I = $I;

        $user = $this->makeUser('user@kidup.dk', true);

        NotificationHelper::emptyNotifications();
        (new NotificationDistributer($user->id))->userRecovery($user, 'recovery-url');
        list($receivedMail, $mailView, $mailParams) = NotificationHelper::getMail();
        list($receivedPush, $pushView, $pushParams) = NotificationHelper::getPush();
        $I->assertTrue($receivedMail);
        $I->assertFalse($receivedPush);
    }

    public function testBookingPayoutOwner(ApiTester $I)
    {
        $this->I = $I;

        /** @var Booking $booking */
        $owner = $this->fm->create(UserMuffin::className());
        UserHelper::login($owner);
        $item = $this->fm->create(ItemMuffin::className(), [
            'owner_id' => $owner->id
        ]);
        $booking = $this->fm->create(BookingMuffin::class, [
            'item_id' => $item->id
        ]);

        NotificationHelper::emptyNotifications();
        (new NotificationDistributer($booking->item->owner_id))->bookingPayoutOwner($booking);
        list($receivedMail, $mailView, $mailParams) = NotificationHelper::getMail();
        list($receivedPush, $pushView, $pushParams) = NotificationHelper::getPush();
        $I->assertTrue($receivedMail);
        $I->assertFalse($receivedPush);
    }

    // Push only (no mail)

    public function testConversationMessageReceived(ApiTester $I)
    {
        $this->I = $I;

        $owner = $this->fm->create(UserMuffin::className());
        UserHelper::login($owner);
        $item = $this->fm->create(ItemMuffin::className(), [
            'owner_id' => $owner->id
        ]);
        $booking = $this->fm->create(BookingMuffin::class, [
            'item_id' => $item->id
        ]);
        $conversation = $this->fm->create(ConversationMuffin::class, [
            'booking_id' => $booking->id
        ]);
        $message = $this->fm->create(MessageMuffin::class, [
            'conversation_id' => $conversation->id
        ]);

        $this->setPushEnabled($message->conversation->targetUser, true);
        NotificationHelper::emptyNotifications();
        (new NotificationDistributer($message->conversation->target_user_id))->conversationMessageReceived($message);
        list($receivedMail, $mailView, $mailParams) = NotificationHelper::getMail();
        list($receivedPush, $pushView, $pushParams) = NotificationHelper::getPush();
        // There is no such e-mail
        $I->assertFalse($receivedMail);
        $I->assertTrue($receivedPush);

        $this->setPushEnabled($message->conversation->targetUser, false);
        NotificationHelper::emptyNotifications();
        (new NotificationDistributer($message->conversation->target_user_id))->conversationMessageReceived($message);
        list($receivedMail, $mailView, $mailParams) = NotificationHelper::getMail();
        list($receivedPush, $pushView, $pushParams) = NotificationHelper::getPush();
        $I->assertFalse($receivedMail);
        $I->assertFalse($receivedPush);
    }

    // Mails with fallbacks

    public function testBookingConfirmedOwner(ApiTester $I)
    {
        $this->I = $I;

        $owner = $this->fm->create(UserMuffin::className());
        UserHelper::login($owner);
        $item = $this->fm->create(ItemMuffin::className(), [
            'owner_id' => $owner->id
        ]);
        $booking = $this->fm->create(BookingMuffin::class, [
            'item_id' => $item->id
        ]);

        $this->setPushEnabled($booking->item->owner, true);
        NotificationHelper::emptyNotifications();
        (new NotificationDistributer($booking->item->owner_id))->bookingConfirmedOwner($booking);
        list($receivedMail, $mailView, $mailParams) = NotificationHelper::getMail();
        list($receivedPush, $pushView, $pushParams) = NotificationHelper::getPush();
        $I->assertTrue($receivedMail);
        $I->assertTrue($receivedPush);

        $this->setPushEnabled($booking->item->owner, false);
        NotificationHelper::emptyNotifications();
        (new NotificationDistributer($booking->item->owner_id))->bookingConfirmedOwner($booking);
        list($receivedMail, $mailView, $mailParams) = NotificationHelper::getMail();
        list($receivedPush, $pushView, $pushParams) = NotificationHelper::getPush();
        $I->assertTrue($receivedMail);
        $I->assertFalse($receivedPush);
    }

    public function testBookingRequestOwner(ApiTester $I)
    {
        $this->I = $I;

        $owner = $this->fm->create(UserMuffin::className());
        UserHelper::login($owner);
        $item = $this->fm->create(ItemMuffin::className(), [
            'owner_id' => $owner->id
        ]);
        $booking = $this->fm->create(BookingMuffin::class, [
            'item_id' => $item->id
        ]);

        $this->setPushEnabled($booking->item->owner, true);
        NotificationHelper::emptyNotifications();
        (new NotificationDistributer($booking->item->owner_id))->bookingRequestOwner($booking);
        list($receivedMail, $mailView, $mailParams) = NotificationHelper::getMail();
        list($receivedPush, $pushView, $pushParams) = NotificationHelper::getPush();
        $I->assertTrue($receivedMail);
        $I->assertTrue($receivedPush);

        $this->setPushEnabled($booking->item->owner, false);
        NotificationHelper::emptyNotifications();
        (new NotificationDistributer($booking->item->owner_id))->bookingRequestOwner($booking);
        list($receivedMail, $mailView, $mailParams) = NotificationHelper::getMail();
        list($receivedPush, $pushView, $pushParams) = NotificationHelper::getPush();
        $I->assertTrue($receivedMail);
        $I->assertFalse($receivedPush);
    }

    public function testBookingStartOwner(ApiTester $I)
    {
        $this->I = $I;

        $owner = $this->fm->create(UserMuffin::className());
        UserHelper::login($owner);
        $item = $this->fm->create(ItemMuffin::className(), [
            'owner_id' => $owner->id
        ]);
        $booking = $this->fm->create(BookingMuffin::class, [
            'item_id' => $item->id
        ]);

        $this->setPushEnabled($booking->item->owner, true);
        NotificationHelper::emptyNotifications();
        (new NotificationDistributer($booking->item->owner_id))->bookingStartOwner($booking);
        list($receivedMail, $mailView, $mailParams) = NotificationHelper::getMail();
        list($receivedPush, $pushView, $pushParams) = NotificationHelper::getPush();
        $I->assertTrue($receivedMail);
        $I->assertTrue($receivedPush);

        $this->setPushEnabled($booking->item->owner, false);
        NotificationHelper::emptyNotifications();
        (new NotificationDistributer($booking->item->owner_id))->bookingStartOwner($booking);
        list($receivedMail, $mailView, $mailParams) = NotificationHelper::getMail();
        list($receivedPush, $pushView, $pushParams) = NotificationHelper::getPush();
        $I->assertTrue($receivedMail);
        $I->assertFalse($receivedPush);
    }

    public function testBookingConfirmedRenter(ApiTester $I)
    {
        $this->I = $I;

        I18nSource::deleteAll(['category' => 'notification.push.booking_confirmed_renter']);
        $message = $this->fm->create(I18nSourceMuffin::class, [
            'category' => 'notification.push.booking_confirmed_renter',
            'message' => 'The rent has been accepted contact {owner_name} and plan the rest.'
        ]);
        $this->fm->create(I18nMessageMuffin::class, [
            'id' => $message->id,
            'language' => 'da-dk',
            'translation' => '{owner_name} har accepteret din forespørgsel på {item_name}.'
        ]);

        $owner = $this->fm->create(UserMuffin::className());
        UserHelper::login($owner, 'da-dk');
        $item = $this->fm->create(ItemMuffin::className(), [
            'owner_id' => $owner->id
        ]);
        /** @var Booking $booking */
        $booking = $this->fm->create(BookingMuffin::class, [
            'item_id' => $item->id
        ]);

        $this->setPushEnabled($booking->renter, true);
        NotificationHelper::emptyNotifications();
        (new NotificationDistributer($booking->renter_id))->bookingConfirmedRenter($booking);
        list($receivedMail, $mailView, $mailParams) = NotificationHelper::getMail();
        list($receivedPush, $pushView, $pushParams) = NotificationHelper::getPush();
        $I->assertTrue($receivedMail);
        $I->assertTrue($receivedPush);

        // Should be translated
        $I->assertNotContains('{item_name}', $pushView);

        $this->setPushEnabled($booking->renter, false);
        NotificationHelper::emptyNotifications();
        (new NotificationDistributer($booking->renter_id))->bookingConfirmedRenter($booking);
        list($receivedMail, $mailView, $mailParams) = NotificationHelper::getMail();
        list($receivedPush, $pushView, $pushParams) = NotificationHelper::getPush();
        $I->assertTrue($receivedMail);
        $I->assertFalse($receivedPush);
    }

    public function testBookingDeclinedRenter(ApiTester $I)
    {
        $this->I = $I;

        $owner = $this->fm->create(UserMuffin::className());
        UserHelper::login($owner);
        $item = $this->fm->create(ItemMuffin::className(), [
            'owner_id' => $owner->id
        ]);
        /** @var Booking $booking */
        $booking = $this->fm->create(BookingMuffin::class, [
            'item_id' => $item->id
        ]);

        $this->setPushEnabled($booking->renter, true);
        NotificationHelper::emptyNotifications();
        (new NotificationDistributer($booking->renter_id))->bookingDeclinedRenter($booking);
        list($receivedMail, $mailView, $mailParams) = NotificationHelper::getMail();
        list($receivedPush, $pushView, $pushParams) = NotificationHelper::getPush();
        $I->assertTrue($receivedMail);
        $I->assertTrue($receivedPush);

        $this->setPushEnabled($booking->renter, false);
        NotificationHelper::emptyNotifications();
        (new NotificationDistributer($booking->renter_id))->bookingDeclinedRenter($booking);
        list($receivedMail, $mailView, $mailParams) = NotificationHelper::getMail();
        list($receivedPush, $pushView, $pushParams) = NotificationHelper::getPush();
        $I->assertTrue($receivedMail);
        $I->assertFalse($receivedPush);
    }

    public function testBookingStartRenter(ApiTester $I)
    {
        $this->I = $I;

        $owner = $this->fm->create(UserMuffin::className());
        UserHelper::login($owner, 'da-dk');
        $item = $this->fm->create(ItemMuffin::className(), [
            'owner_id' => $owner->id
        ]);
        /** @var Booking $booking */
        $booking = $this->fm->create(BookingMuffin::class, [
            'item_id' => $item->id
        ]);

        $this->setPushEnabled($booking->renter, true);
        NotificationHelper::emptyNotifications();
        (new NotificationDistributer($booking->renter_id))->bookingStartRenter($booking);
        list($receivedMail, $mailView, $mailParams) = NotificationHelper::getMail();
        list($receivedPush, $pushView, $pushParams) = NotificationHelper::getPush();
        $I->assertTrue($receivedMail, 'Should receive an e-mail');
        $I->assertTrue($receivedPush, 'Should receive a push notification');

        $this->setPushEnabled($booking->renter, false);
        NotificationHelper::emptyNotifications();
        (new NotificationDistributer($booking->renter_id))->bookingStartRenter($booking);
        list($receivedMail, $mailView, $mailParams) = NotificationHelper::getMail();
        list($receivedPush, $pushView, $pushParams) = NotificationHelper::getPush();
        $I->assertTrue($receivedMail);
        $I->assertFalse($receivedPush);
    }

    // Just test the logging for one push notification and for one mail notification
    public function testMailLog(ApiTester $I)
    {
        $this->I = $I;
        NotificationHelper::emptyNotifications();

        // Trigger user create event
        $faker = \Faker\Factory::create();
        $email = $faker->freeEmail;
        $I->sendPOST('users', [
            'email' => $email,
            'password' => $faker->password(6),
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
        ]);

        list($receivedMail, $mailView, $mailParams) = NotificationHelper::getMail();
        $logs = NotificationMailLog::find()->all();
        $I->assertEquals(1, count($logs), 'There should exactly be one log item.');
        $log = reset($logs);
        $I->assertEquals($email, $log->to, 'Receiver should be correct.');
        $I->assertNotEquals(0, strlen($log->hash), 'Hash should be set.');

        // Test whether it is visible at the endpoint
        $I->sendGET('notifications/mail-view', [
            'hash' => $log->hash
        ]);
        $I->assertEquals($log->view, $I->grabResponse(), 'Response should be equal.');

    }

    public function testPushLog(ApiTester $I)
    {
        $this->I = $I;

        $owner = $this->fm->create(UserMuffin::className());
        UserHelper::login($owner);
        $item = $this->fm->create(ItemMuffin::className(), [
            'owner_id' => $owner->id
        ]);
        $booking = $this->fm->create(BookingMuffin::class, [
            'item_id' => $item->id
        ]);
        $conversation = $this->fm->create(ConversationMuffin::class, [
            'booking_id' => $booking->id
        ]);
        $message = $this->fm->create(MessageMuffin::class, [
            'conversation_id' => $conversation->id
        ]);

        $this->setPushEnabled($message->conversation->targetUser, true);
        NotificationHelper::emptyNotifications();
        (new NotificationDistributer($message->conversation->target_user_id))->conversationMessageReceived($message);
        list($receivedPush, $pushView, $pushParams) = NotificationHelper::getPush();

        $logs = NotificationPushLog::find()->all();
        $I->assertEquals(1, count($logs), 'There should exactly be one log item.');
        $log = reset($logs);
        $I->assertEquals($pushView, $log->view);
        $I->assertNotEquals(0, strlen($log->hash));
    }

}