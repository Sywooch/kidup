<?php
namespace mail\components;

class MailType
{

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

    public static function getBindings()
    {
        return [
            \mail\mails\bookingRenter\Confirmation::class => self::BOOKING_RENTER_CONFIRMATION,
            \mail\mails\bookingRenter\Decline::class => self::BOOKING_RENTER_DECLINE,
            \mail\mails\bookingRenter\Failed::class => self::BOOKING_RENTER_PAYMENT_FAILED,
            \mail\mails\bookingRenter\Receipt::class => self::BOOKING_RENTER_RECEIPT,
            \mail\mails\bookingRenter\Request::class => self::BOOKING_RENTER_REQUEST,
            \mail\mails\bookingRenter\Start::class => self::BOOKING_RENTER_STARTS,

            \mail\mails\bookingOwner\Confirmation::class => self::BOOKING_OWNER_CONFIRMATION,
            \mail\mails\bookingOwner\Failed::class => self::BOOKING_OWNER_PAYMENT_FAILED,
            \mail\mails\bookingOwner\Payout::class => self::BOOKING_OWNER_PAYOUT,
            \mail\mails\bookingOwner\Request::class => self::BOOKING_OWNER_REQUEST,
            \mail\mails\bookingOwner\Start::class => self::BOOKING_OWNER_STARTS,

            \mail\mails\conversation\NewMessage::class => self::MESSAGE,
            \mail\mails\item\UnfinishedReminder::class => self::ITEM_UNPUBLISHED_REMINDER,

            \mail\mails\review\Publish::class => self::REVIEW_PUBLIC,
            \mail\mails\review\Reminder::class => self::REVIEW_REMINDER,
            \mail\mails\review\Request::class => self::REVIEW_REQUEST,

            \mail\mails\user\Reconfirm::class => self::USER_RECONFIRM,
            \mail\mails\user\Recovery::class => self::USER_RECOVERY,
            \mail\mails\user\Welcome::class => self::USER_WELCOME,
        ];
    }

    /**
     * Create a model instance based on the given type.
     *
     * @param $type Type.
     * @return bool|object False if there was no model found of the given type, an instance of the model otherwise.
     */
    public static function getModel($type)
    {
        $bindings = self::getBindings();
        foreach ($bindings as $className => $classType) {
            if ($type == $classType) {
                return new $className();
            }
        }
        return false;
    }

    /**
     * Get the type of a model.
     *
     * @param object $model Model to find the type of.
     * @return bool|string False if no type could be found, the type otherwise..
     */
    public static function getType($model)
    {
        $bindings = self::getBindings();
        foreach ($bindings as $className => $classType) {
            if (get_class($model) == $className) {
                return $classType;
            }
        }
        return false;
    }

}