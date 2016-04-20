<?php
// @todo test if correct sender ID
namespace notification;

use app\components\Event;
use admin\jobs\SlackJob;
use booking\models\booking\Booking;
use booking\models\payin\Payin;
use message\models\message\Message;
use notification\components\NotificationDistributer;
use user\models\token\Token;
use user\models\token\TokenFactory;
use user\models\user\User;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module;
use yii\db\ActiveRecord;
use yii\helpers\Url;

class Bootstrap implements BootstrapInterface
{

    /** @inheritdoc */
    public function bootstrap($app)
    {
        /** @var $module Module */
        if ($app->hasModule('notification') && ($module = $app->getModule('notification')) instanceof Module) {
            Yii::setAlias('@notification', __DIR__);
            Yii::setAlias('@notificationAssets', Yii::getAlias('@web') . '/assets_web/modules/notification');
        }

        Yii::setAlias('@notification', '@app/modules/notification');
        Yii::setAlias('@notification-view', '@notification/views/');
        Yii::setAlias('@notification-mail', '@notification-view/mail/');
        Yii::setAlias('@notification-push', '@notification-view/push/');
        Yii::setAlias('@notification-layouts', '@notification-view/layouts/');
        Yii::setAlias('@notification-assets', '@notification/assets/');

        $anonFunction = function ($event) {
            /**
             * @var User $user
             */
            $user = $event->sender;
            new SlackJob([
                'message' => "New user with id {$user->id} registered " . \yii\helpers\StringHelper::truncate(Url::previous(), 50)
            ]);
            (new NotificationDistributer($user->id))->userWelcome($user);
        };

        Event::register(User::className(), User::EVENT_USER_CREATE_DONE, $anonFunction);

        Event::register(User::className(), User::EVENT_USER_REGISTER_DONE, $anonFunction);

        Event::register(User::className(), User::EVENT_USER_REQUEST_EMAIL_RECONFIRM, function ($event) {
            /**
             * @var User $user
             */
            $user = $event->sender;
            (new NotificationDistributer($user->id))->userReconfirm($user);
        });

        Event::register(User::className(), User::EVENT_USER_REQUEST_RECOVERY, function ($event) {
            /**
             * @var User $user
             */
            $user = $event->sender;
            $url = TokenFactory::createRecovery($user)->getUrl();
            (new NotificationDistributer($user->id))->userRecovery($user, $url);
        });

        \yii\base\Event::on(Message::className(), ActiveRecord::EVENT_AFTER_INSERT, function($event){
            /**
             * @var Message $message
             */
            $message = $event->sender;
            (new NotificationDistributer($message->receiver_user_id))->conversationMessageReceived($message);
        });

        Event::register(Booking::className(), Booking::EVENT_OWNER_ACCEPTED, function($event) {
            /**
             * @var Booking $booking
             */
            $booking = $event->sender;
            (new NotificationDistributer($booking->item->owner_id))->bookingConfirmedOwner($booking);
            (new NotificationDistributer($booking->renter_id))->bookingConfirmedRenter($booking);
        });

        Event::register(Booking::className(), Booking::EVENT_OWNER_DECLINES, function ($event) {
            /**
             * @var Booking $booking
             */
            $booking = $event->sender;
            (new NotificationDistributer($booking->renter_id))->bookingDeclinedRenter($booking);
        });

        Event::register(Booking::className(), Booking::EVENT_OWNER_NO_RESPONSE, function ($event) {
            /**
             * @var Booking $booking
             */
            $booking = $event->sender;
            (new NotificationDistributer($booking->renter_id))->bookingDeclinedRenter($booking);
        });

        Event::register(Booking::className(), Booking::EVENT_BOOKING_ALMOST_START, function ($event) {
            /**
             * @var Booking $booking
             */
            $booking = $event->sender;
            (new NotificationDistributer($booking->item->owner_id))->bookingStartOwner($booking);
            (new NotificationDistributer($booking->renter_id))->bookingStartRenter($booking);
        });

        Event::register(Payin::className(), Payin::EVENT_PAYIN_CONFIRMED, function ($event) {
            /**
             * @var Payin $payin
             */
            $payin = $event->sender;
            (new NotificationDistributer($payin->booking->item->owner_id))->bookingRequestOwner($payin->booking);
        });

        Event::register(Booking::className(), Booking::EVENT_OWNER_INVOICE_READY, function ($event) {
            /**
             * @var Booking $booking
             */
            $booking = $event->sender;
            (new NotificationDistributer($booking->item->owner_id))->bookingPayoutOwner($booking);
        });
        
    }
}