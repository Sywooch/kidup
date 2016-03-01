<?php

namespace mail;

use app\helpers\Event;
use app\jobs\SlackJob;
use booking\models\Booking;
use booking\models\Payin;
use item\models\Item;
use mail\mails\bookingOwner\StartFactory;
use mail\mails\bookingRenter\DeclineFactory;
use mail\mails\conversation\NewMessage;
use mail\mails\item\UnfinishedReminder;
use mail\mails\user\RecoveryFactory;
use mail\mails\user\WelcomeFactory;
use message\models\Message;
use user\models\User;
use mail\mails\user\ReconfirmFactory;
use mail\components\MailSender;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class Bootstrap implements BootstrapInterface
{

    /** @inheritdoc */
    public function bootstrap($app)
    {
        /** @var $module Module */
        if ($app->hasModule('mail') && ($module = $app->getModule('mail')) instanceof Module) {
            Yii::setAlias('@mail', __DIR__);
            Yii::setAlias('@mailAssets', Yii::getAlias('@web') . '/assets_web/modules/mail');
        }


        // user
        Event::register(User::className(), User::EVENT_USER_REGISTER_DONE, function ($event) {
            new SlackJob([
                'message' => "New user registered ".\yii\helpers\StringHelper::truncate(Url::previous(),50)
            ]);
            MailSender::send((new \mail\mails\user\WelcomeFactory())->create($event->sender));
        });

        Event::register(User::className(), User::EVENT_USER_CREATE_DONE, function ($event) {
            new SlackJob([
                'message' => "New user registered ".\yii\helpers\StringHelper::truncate(Url::previous(),50)
            ]);
            MailSender::send((new \mail\mails\user\WelcomeFactory())->create($event->sender));
        });

        Event::register(User::className(), User::EVENT_USER_REQUEST_EMAIL_RECONFIRM, function ($event) {
            MailSender::send((new \mail\mails\user\ReconfirmFactory())->create($event->sender));
        });

        Event::register(User::className(), User::EVENT_USER_REQUEST_RECOVERY, function ($event) {
            MailSender::send((new \mail\mails\user\RecoveryFactory())->create($event->sender));
        });

        // item
        Event::register(Item::className(), Item::EVENT_UNFINISHED_REMINDER, function ($event) {
            MailSender::send((new \mail\mails\item\UnfinishedReminder())->create($event->sender));
        });

        // message
        Event::register(Message::className(), Message::EVENT_NEW_MESSAGE, function ($event) {
            MailSender::send((new \mail\mails\conversation\NewMessageFactory())->create($event->sender));
        });

        // booking owner
        Event::register(Booking::className(), Booking::EVENT_OWNER_DECLINES, function ($event) {
            MailSender::send((new \mail\mails\bookingRenter\DeclineFactory())->create($event->sender));
        });

        Event::register(Booking::className(), Booking::EVENT_OWNER_NO_RESPONSE, function ($event) {
            MailSender::send((new \mail\mails\bookingRenter\DeclineFactory())->create($event->sender));
        });

        Event::register(Booking::className(), Booking::EVENT_BOOKING_ALMOST_START, function ($event) {
            MailSender::send((new \mail\mails\bookingOwner\StartFactory())->create($event->sender));
            MailSender::send((new \mail\mails\bookingRenter\StartFactory())->create($event->sender));
        });

        Event::register(Payin::className(), Payin::EVENT_FAILED, function ($event) {
            MailSender::send((new \mail\mails\bookingOwner\FailedFactory())->create($event->sender->booking));
            MailSender::send((new \mail\mails\bookingRenter\FailedFactory())->create($event->sender->booking));
        });

        Event::register(Payin::className(), Payin::EVENT_AUTHORIZATION_CONFIRMED, function ($event) {
            Yii::trace('EVENT_AUTHORIZATION_CONFIRMED');
            MailSender::send((new \mail\mails\bookingRenter\RequestFactory())->create($event->sender->booking));
            MailSender::send((new \mail\mails\bookingOwner\RequestFactory())->create($event->sender->booking));
        });

        Event::register(Booking::className(), Booking::EVENT_OWNER_CONFIRMATION_REMINDER, function ($event) {
            MailSender::send((new \mail\mails\bookingOwner\RequestFactory())->create($event->sender));
        });

        Event::register(Payin::className(), Payin::EVENT_PAYIN_CONFIRMED, function ($event) {
            Yii::trace('EVENT_PAYIN_CONFIRMED');
            MailSender::send((new \mail\mails\bookingRenter\ConfirmationFactory())->create($event->sender->booking));
            MailSender::send((new \mail\mails\bookingOwner\ConfirmationFactory())->create($event->sender->booking));
            MailSender::send((new \mail\mails\bookingRenter\ReceiptFactory())->create($event->sender->booking));
        });

        Event::register(Booking::className(), Booking::EVENT_OWNER_INVOICE_READY, function ($event) {
            MailSender::send((new \mail\mails\bookingOwner\PayoutFactory())->create($event->sender));
        });

        // reviews+
        Event::register(Booking::className(), Booking::EVENT_BOOKING_ENDED, function ($event) {
            MailSender::send((new \mail\mails\review\RequestFactory())->create($event->sender, true));
            MailSender::send((new \mail\mails\review\RequestFactory())->create($event->sender, false));
        });

        Event::register(Booking::className(), Booking::EVENT_REVIEW_REMINDER_OWNER, function ($event) {
            MailSender::send((new \mail\mails\review\ReminderFactory())->create($event->sender, true));
        });
        Event::register(Booking::className(), Booking::EVENT_REVIEW_REMINDER_RENTER, function ($event) {
            MailSender::send((new \mail\mails\review\ReminderFactory())->create($event->sender, false));
        });

        Event::register(Booking::className(), Booking::EVENT_REVIEWS_PUBLIC, function ($event) {
            MailSender::send((new \mail\mails\review\PublishFactory())->create($event->sender, true));
        });
    }
}