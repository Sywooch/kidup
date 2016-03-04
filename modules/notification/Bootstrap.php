<?php

namespace notification;

use app\helpers\Event;
use app\jobs\SlackJob;
use booking\models\Booking;
use booking\models\Payin;
use item\models\Item;
use notifications\mails\bookingOwner\StartFactory;
use notifications\mails\bookingRenter\DeclineFactory;
use notifications\mails\conversation\NewMessage;
use notifications\mails\item\UnfinishedReminder;
use notifications\mails\user\RecoveryFactory;
use notifications\mails\user\WelcomeFactory;
use message\models\Message;
use user\models\User;
use notifications\mails\user\ReconfirmFactory;
use notifications\components\MailSender;
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

        // user
        Event::register(User::className(), User::EVENT_USER_REGISTER_DONE, function ($event) {
            new SlackJob([
                'message' => "New user registered ".\yii\helpers\StringHelper::truncate(Url::previous(),50)
            ]);
            // @todo

            //MailSender::send((new \mail\mails\user\WelcomeFactory())->create($event->sender));
        });

        Event::register(User::className(), User::EVENT_USER_CREATE_DONE, function ($event) {
            new SlackJob([
                'message' => "New user registered ".\yii\helpers\StringHelper::truncate(Url::previous(),50)
            ]);
            // @todo
            //MailSender::send((new \mail\mails\user\WelcomeFactory())->create($event->sender));
        });

        Event::register(User::className(), User::EVENT_USER_REQUEST_EMAIL_RECONFIRM, function ($event) {
            // @todo
            //MailSender::send((new \mail\mails\user\ReconfirmFactory())->create($event->sender));
        });

        Event::register(User::className(), User::EVENT_USER_REQUEST_RECOVERY, function ($event) {
            // @todo
            //MailSender::send((new \mail\mails\user\RecoveryFactory())->create($event->sender));
        });

        // item
        Event::register(Item::className(), Item::EVENT_UNFINISHED_REMINDER, function ($event) {
            // @todo
            //MailSender::send((new \mail\mails\item\UnfinishedReminder())->create($event->sender));
        });

        // message
        Event::register(Message::className(), Message::EVENT_NEW_MESSAGE, function ($event) {
            // @todo
            //MailSender::send((new \mail\mails\conversation\NewMessageFactory())->create($event->sender));
        });

        // booking owner
        Event::register(Booking::className(), Booking::EVENT_OWNER_DECLINES, function ($event) {
            // @todo
            //MailSender::send((new \mail\mails\bookingRenter\DeclineFactory())->create($event->sender->booking));
        });

        Event::register(Booking::className(), Booking::EVENT_OWNER_NO_RESPONSE, function ($event) {
            // @todo
            //MailSender::send((new \mail\mails\bookingRenter\DeclineFactory())->create($event->sender->booking));
        });

        Event::register(Booking::className(), Booking::EVENT_BOOKING_ALMOST_START, function ($event) {
            // @todo
            //MailSender::send((new \mail\mails\bookingOwner\StartFactory())->create($event->sender->booking));
            //MailSender::send((new \mail\mails\bookingRenter\StartFactory())->create($event->sender->booking));
        });

        Event::register(Payin::className(), Payin::EVENT_FAILED, function ($event) {
            // @todo
            //MailSender::send((new \mail\mails\bookingOwner\FailedFactory())->create($event->sender->booking));
            //MailSender::send((new \mail\mails\bookingRenter\FailedFactory())->create($event->sender->booking));
        });

        Event::register(Payin::className(), Payin::EVENT_AUTHORIZATION_CONFIRMED, function ($event) {
            Yii::trace('EVENT_AUTHORIZATION_CONFIRMED');
            // @todo
            //MailSender::send((new \mail\mails\bookingRenter\RequestFactory())->create($event->sender->booking));
            //MailSender::send((new \mail\mails\bookingOwner\RequestFactory())->create($event->sender->booking));
        });

        Event::register(Booking::className(), Booking::EVENT_OWNER_CONFIRMATION_REMINDER, function ($event) {
            // @todo
            //MailSender::send((new \mail\mails\bookingOwner\RequestFactory())->create($event->sender->booking));
        });

        Event::register(Payin::className(), Payin::EVENT_PAYIN_CONFIRMED, function ($event) {
            Yii::trace('EVENT_PAYIN_CONFIRMED');
            // @todo
            //MailSender::send((new \mail\mails\bookingRenter\ConfirmationFactory())->create($event->sender->booking));
            //MailSender::send((new \mail\mails\bookingOwner\ConfirmationFactory())->create($event->sender->booking));
            //MailSender::send((new \mail\mails\bookingRenter\ReceiptFactory())->create($event->sender->booking));
        });

        Event::register(Booking::className(), Booking::EVENT_OWNER_INVOICE_READY, function ($event) {
            // @todo
            //MailSender::send((new \mail\mails\bookingOwner\PayoutFactory())->create($event->sender->booking));
        });

        // reviews+
        Event::register(Booking::className(), Booking::EVENT_BOOKING_ENDED, function ($event) {
            // @todo
            //MailSender::send((new \mail\mails\review\RequestFactory())->create($event->sender->booking, true));
            //MailSender::send((new \mail\mails\review\RequestFactory())->create($event->sender->booking, false));
        });

        Event::register(Booking::className(), Booking::EVENT_REVIEW_REMINDER_OWNER, function ($event) {
            // @todocircle.yml
            //MailSender::send((new \mail\mails\review\ReminderFactory())->create($event->sender->booking, true));
        });
        Event::register(Booking::className(), Booking::EVENT_REVIEW_REMINDER_RENTER, function ($event) {
            // @todo
            //MailSender::send((new \mail\mails\review\ReminderFactory())->create($event->sender->booking, false));
        });

        Event::register(Booking::className(), Booking::EVENT_REVIEWS_PUBLIC, function ($event) {
            // @todo
            //MailSender::send((new \mail\mails\review\PublishFactory())->create($event->sender->booking, true));
        });
    }
}