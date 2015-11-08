<?php

namespace mail;

use app\helpers\Event;
use \booking\models\Booking;
use \booking\models\Payin;
use \item\models\Item;
use mail\mails\user\ReconfirmFactory;
use \mail\models\Mailer;
use mail\widgets\button\Button;
use \message\models\Message;
use \user\models\User;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module;
use yii\helpers\ArrayHelper;

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
            MailSender::send(ReconfirmFactory::create($event->sender));

            Mailer::send(Mailer::USER_WELCOME, $event->sender);
        });
        Event::register(User::className(), User::EVENT_USER_CREATE_DONE, function ($event) {
            Mailer::send(Mailer::USER_WELCOME, $event->sender);
        });

        Event::register(User::className(), User::EVENT_USER_REQUEST_EMAIL_RECONFIRM, function ($event) {
            Mailer::send(Mailer::USER_RECONFIRM, $event->sender);
        });

        Event::register(User::className(), User::EVENT_USER_REQUEST_RECOVERY, function ($event) {
            Mailer::send(Mailer::USER_RECOVERY, $event->sender);
        });

        // item
        Event::register(Item::className(), Item::EVENT_UNFINISHED_REMINDER, function ($event) {
            Mailer::send(Mailer::ITEM_UNPUBLISHED_REMINDER, $event->sender);
        });

        // message
        Event::register(Message::className(), Message::EVENT_NEW_MESSAGE, function ($event) {
            Mailer::send(Mailer::MESSAGE, $event->sender);
        });

        // booking owner
        Event::register(Booking::className(), Booking::EVENT_OWNER_DECLINES, function ($event) {
            Mailer::send(Mailer::BOOKING_RENTER_DECLINE, $event->sender);
        });

        Event::register(Booking::className(), Booking::EVENT_OWNER_NO_RESPONSE, function ($event) {
            Mailer::send(Mailer::BOOKING_RENTER_DECLINE, $event->sender);
        });

        Event::register(Booking::className(), Booking::EVENT_BOOKING_CANCELLED_BY_RENTER, function ($event) {
            Mailer::send(Mailer::BOOKING_OWNER_CANCELLED, $event->sender);
        });

        Event::register(Booking::className(), Booking::EVENT_BOOKING_ALMOST_START, function ($event) {
            Mailer::send(Mailer::BOOKING_OWNER_STARTS, $event->sender);
            Mailer::send(Mailer::BOOKING_RENTER_STARTS, $event->sender);
        });

        Event::register(Payin::className(), Payin::EVENT_FAILED, function ($event) {
            Mailer::send(Mailer::BOOKING_OWNER_PAYMENT_FAILED, $event->sender->booking);
            Mailer::send(Mailer::BOOKING_RENTER_PAYMENT_FAILED, $event->sender->booking);
        });

        Event::register(Payin::className(), Payin::EVENT_AUTHORIZATION_CONFIRMED, function ($event) {
            Yii::trace('EVENT_AUTHORIZATION_CONFIRMED');
            Mailer::send(Mailer::BOOKING_RENTER_REQUEST, $event->sender->booking);
            Mailer::send(Mailer::BOOKING_OWNER_REQUEST, $event->sender->booking);
        });

        Event::register(Booking::className(), Booking::EVENT_OWNER_CONFIRMATION_REMINDER, function ($event) {
            Mailer::send(Mailer::BOOKING_OWNER_REQUEST, $event->sender);
        });

        Event::register(Payin::className(), Payin::EVENT_PAYIN_CONFIRMED, function ($event) {
            Yii::trace('EVENT_PAYIN_CONFIRMED');
            Mailer::send(Mailer::BOOKING_RENTER_CONFIRMATION, $event->sender->booking);
            Mailer::send(Mailer::BOOKING_OWNER_CONFIRMATION, $event->sender->booking);
            Mailer::send(Mailer::BOOKING_RENTER_RECEIPT, $event->sender->booking);
        });

        Event::register(Booking::className(), Booking::EVENT_OWNER_INVOICE_READY, function ($event) {
            Mailer::send(Mailer::BOOKING_OWNER_PAYOUT, $event->sender);
        });

        // reviews+
        Event::register(Booking::className(), Booking::EVENT_BOOKING_ENDED, function ($event) {
            Mailer::send(Mailer::REVIEW_REQUEST, ['booking' => $event->sender, 'isOwner' => true]); // owner and renter
            Mailer::send(Mailer::REVIEW_REQUEST, ['booking' => $event->sender, 'isOwner' => false]);
        });

        Event::register(Booking::className(), Booking::EVENT_REVIEW_REMINDER_OWNER, function ($event) {
            Mailer::send(Mailer::REVIEW_REMINDER, ['booking' => $event->sender, 'isOwner' => true]);
        });
        Event::register(Booking::className(), Booking::EVENT_REVIEW_REMINDER_RENTER, function ($event) {
            Mailer::send(Mailer::REVIEW_REMINDER, ['booking' => $event->sender, 'isOwner' => false]);
        });

        Event::register(Booking::className(), Booking::EVENT_REVIEWS_PUBLIC, function ($event) {
            Mailer::send(Mailer::REVIEW_PUBLIC, ['booking' => $event->sender, 'isOwner' => false]);
            Mailer::send(Mailer::REVIEW_PUBLIC, ['booking' => $event->sender, 'isOwner' => true]);
        });
    }
}