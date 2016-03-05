<?php

namespace notification;

use app\helpers\Event;
use app\jobs\SlackJob;
use booking\models\booking\Booking;
use booking\models\payin\Payin;
use item\models\item\Item;
use message\models\message\Message;
use notification\components\NotificationDistributer;
use notifications\components\MailSender;
use notifications\mails\bookingOwner\StartFactory;
use notifications\mails\bookingRenter\DeclineFactory;
use notifications\mails\conversation\NewMessage;
use notifications\mails\item\UnfinishedReminder;
use notifications\mails\user\ReconfirmFactory;
use notifications\mails\user\RecoveryFactory;
use notifications\mails\user\WelcomeFactory;
use user\models\User;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module;
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

        $onNewUser = function ($event) {
            $user = $event->sender;
            $message = "New user registered " . \yii\helpers\StringHelper::truncate(Url::previous(), 50);
            if (!is_null($user->profile->getName())) {
                $message .= " please welcome " . $user->profile->getName();
            }
            new SlackJob([
                'message' => $message
            ]);
            (new NotificationDistributer($user->id))->userWelcome($user);
        };

        // user
        Event::register(User::className(), User::EVENT_USER_REGISTER_DONE, $onNewUser);

        Event::register(User::className(), User::EVENT_USER_CREATE_DONE, $onNewUser);

        Event::register(User::className(), User::EVENT_USER_REQUEST_EMAIL_RECONFIRM, function ($event) {
            (new NotificationDistributer($event->sender->id))->userReconfirm($event->sender);
        });

        Event::register(User::className(), User::EVENT_USER_REQUEST_RECOVERY, function ($event) {
            (new NotificationDistributer($event->sender->id))->userRecovery($event->sender);
        });

        Event::register(Message::className(), Message::EVENT_NEW_MESSAGE, function ($event) {
            (new NotificationDistributer($event->sender->id))->conversationMessageReceived($event->sender->conversation);
        });

        Event::register(Booking::className(), Booking::EVENT_OWNER_DECLINES, function ($event) {
            (new NotificationDistributer($event->booking->id))->bookingDeclinedRenter($event->sender->conversation);
        });

        Event::register(Booking::className(), Booking::EVENT_OWNER_NO_RESPONSE, function ($event) {
            (new NotificationDistributer($event->sender->id))->bookingDeclinedRenter($event->sender);
        });

        Event::register(Booking::className(), Booking::EVENT_BOOKING_ALMOST_START, function ($event) {
            (new NotificationDistributer($event->sender->id))->bookingStartOwner($event->sender);
            (new NotificationDistributer($event->sender->id))->bookingStartRenter($event->sender);
        });

        Event::register(Payin::className(), Payin::EVENT_PAYIN_CONFIRMED, function ($event) {
            (new NotificationDistributer($event->sender->id))->bookingRequestOwner($event->sender);
        });

        Event::register(Booking::className(), Booking::EVENT_OWNER_INVOICE_READY, function ($event) {
            (new NotificationDistributer($event->sender->id))->bookingPayoutOwner($event->sender);
        });
    }
}