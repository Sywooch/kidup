<?php
namespace mail\mails;

class MailType {

    const BOOKING_RENTER_CONFIRMATION = 'BookingRenter.confirmation';
    const BOOKING_RENTER_DECLINE = 'BookingRenter.decline';
    const BOOKING_RENTER_RECEIPT = 'BookingRenter.receipt';
    const BOOKING_RENTER_REQUEST = 'BookingRenter.request';
    const BOOKING_RENTER_STARTS = 'BookingRenter.start';
    const BOOKING_RENTER_PAYMENT_FAILED = 'BookingRenter.failed';

    const BOOKING_OWNER_CONFIRMATION = 'BookingOwner.confirmation';
    const BOOKING_OWNER_PAYOUT = 'BookingOwner.payout';
    const BOOKING_OWNER_REQUEST = 'BookingOwner.request';
    const BOOKING_OWNER_STARTS = 'BookingOwner.start';
    const BOOKING_OWNER_PAYMENT_FAILED = 'BookingOwner.failed';

    const REVIEW_PUBLIC = 'Review.published';
    const REVIEW_REMINDER = 'Review.reminder';
    const REVIEW_REQUEST = 'Review.request';

    const ITEM_UNPUBLISHED_REMINDER = 'Item.unfinishedReminder';

    const MESSAGE = 'Conversation.newMessage';

    const USER_RECONFIRM = 'User.reconfirm';
    const USER_WELCOME = 'User.welcome';
    const USER_RECOVERY = 'User.recovery';

    const TYPE_NOT_DEFINED = 'Undefined';

    public static function getBindings() {
        return [
            bookingRenter\Confirmation::class => self::BOOKING_RENTER_CONFIRMATION,
            bookingRenter\Decline::class => self::BOOKING_RENTER_DECLINE,
            bookingRenter\Failed::class => self::BOOKING_RENTER_PAYMENT_FAILED,
            bookingRenter\Receipt::class => self::BOOKING_RENTER_RECEIPT,
            bookingRenter\Request::class => self::BOOKING_RENTER_REQUEST,
            bookingRenter\Start::class => self::BOOKING_RENTER_STARTS,

            bookingOwner\Confirmation::class => self::BOOKING_OWNER_CONFIRMATION,
            bookingOwner\Failed::class => self::BOOKING_OWNER_PAYMENT_FAILED,
            bookingOwner\Payout::class => self::BOOKING_OWNER_PAYOUT,
            bookingOwner\Request::class => self::BOOKING_OWNER_REQUEST,
            bookingOwner\Start::class => self::BOOKING_OWNER_STARTS,

            conversation\NewMessage::class => self::MESSAGE,
            item\UnfinishedReminder::class => self::ITEM_UNPUBLISHED_REMINDER,

            review\Publish::class => self::REVIEW_PUBLIC,
            review\Reminder::class => self::REVIEW_REMINDER,
            review\Request::class => self::REVIEW_REQUEST,

            user\Reconfirm::class => self::USER_RECONFIRM,
            user\Recovery::class => self::USER_RECOVERY,
            user\Welcome::class => self::USER_WELCOME,
        ];
    }

    public static function getModel($type) {
        $bindings = self::getBindings();
        foreach ($bindings as $className => $classType) {
            if ($type == $classType) {
                return new $className();
            }
        }
    }

    public static function getType($model) {
        $bindings = self::getBindings();
        foreach ($bindings as $className => $classType) {
            if (get_class($model) == $className) {
                return $classType;
            }
        }
    }

}