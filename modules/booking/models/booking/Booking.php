<?php

namespace booking\models\booking;

use app\components\Exception;
use app\components\Event;
use admin\jobs\SlackJob;
use booking\models\payin\Payin;
use booking\models\payin\PayinException;
use message\models\conversation\Conversation;
use message\models\conversation\ConversationFactory;
use message\models\message\MessageFactory;
use user\models\user\User;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;

/**
 * This is the model class for table "Booking".
 */
class BookingException extends Exception
{
}

class NoAccessToBookingException extends BookingException
{
}


class Booking extends BookingBase
{
    const AWAITING_PAYMENT = 'awaiting_payment';
    const PENDING = 'pending_owner';
    const NO_RESPONSE = 'no_response';
    const DECLINED = 'declined';
    const ACCEPTED = 'accepted';
    const CANCELLED = 'cancelled';

    const EVENT_OWNER_ACCEPTED = 'owner_accepts';
    const EVENT_OWNER_DECLINES = 'owner_declines';
    const EVENT_OWNER_INVOICE_READY = 'owner_invoice_ready';
    const EVENT_OWNER_NO_RESPONSE = 'owner_no_resp';
    const EVENT_OWNER_CONFIRMATION_REMINDER = 'owner_confirmation_reminder';

    const EVENT_BOOKING_ALMOST_START = 'booking_almost_starts';
    const EVENT_BOOKING_ALMOST_ENDS = 'booking_almost_ends';
    const EVENT_BOOKING_ENDED = 'booking_ended';
    const EVENT_BOOKING_CANCELLED_BY_RENTER = 'cancelled_by_renter';

    const EVENT_REVIEW_REMINDER_OWNER = 'event_review_reminder_owner';
    const EVENT_REVIEW_REMINDER_RENTER = 'event_review_reminder_renter';
    const EVENT_REVIEWS_PUBLIC = 'event_reviews_public';

    public function isOwner()
    {
        $yuid = \Yii::$app->user->id;
        return $yuid === $this->renter_id || $yuid === $this->item->owner_id;
    }


    public function ownerAccepts()
    {
        if ($this->item->owner_id != \Yii::$app->user->id) {
            throw new ForbiddenHttpException("You are not the owner of this item");
        }
        if (!$this->item->owner->hasValidPayoutMethod()) {
            throw new NoAccessToBookingException("There is no valid payout method set.");
        }
        /**
         * @var Payin $payin
         */
        $payin = Payin::find()->where(['id' => $this->payin_id])->one();
        try {
            $payin->capture();
            $this->status = self::ACCEPTED;
            $this->save();
            Event::trigger($this, self::EVENT_OWNER_ACCEPTED);
        } catch (PayinException $e) {
            throw new BookingException("Payin capture failed", null, $e);
        }

        return false;
    }

    public function canBeAccessedByUser(User $user)
    {
        if ($user->id == $this->renter_id) {
            return true;
        }
        if ($user->id == $this->item->owner_id) {
            return true;
        }
        return false;
    }

    public function ownerDeclines()
    {
        if ($this->item->owner_id != \Yii::$app->user->id) {
            throw new NoAccessToBookingException("You are not the owner of this item");
        }

        /**
         * @var Payin $payin
         */
        $payin = Payin::findOne($this->payin_id);
        if ($payin->release()) {
            $this->status = self::DECLINED;
            $this->save();
            Event::trigger($this, self::EVENT_OWNER_DECLINES);

            return true;
        }

        return false;
    }

    public function ownerFailsToRespond()
    {
        /**
         * @var Payin $payin
         */
        $payin = Payin::findOne($this->payin_id);
        if ($payin->release()) {
            $this->status = self::NO_RESPONSE;
            $this->save();
            Event::trigger($this, self::EVENT_OWNER_NO_RESPONSE);

            return true;
        } else {
            new SlackJob(['message' => "Owner failed to respond: payin release failed " . $payin->id]);
        }

        return false;
    }

    /**
     * Get the name of the current booking status
     * @return false|string
     */
    public function getStatusName()
    {
        $statusses = [
            self::AWAITING_PAYMENT => \Yii::t('booking.status.awaiting_payment', 'Awaiting payment'),
            self::PENDING => \Yii::t('booking.status.pending', 'Pending'),
            self::NO_RESPONSE => \Yii::t('booking.status.no_response', 'No responds by Owner'),
            self::DECLINED => \Yii::t('booking.status.refused', 'Refused'),
            self::ACCEPTED => \Yii::t('booking.status.accepted', 'Accepted'),
            self::CANCELLED => \Yii::t('booking.status.cancelled', 'Cancelled')
        ];

        return isset($statusses[$this->status]) ? $statusses[$this->status] : false;
    }

    /**
     * Get the number of days of this booking.
     *
     * @return int The number of days this booking lasts.
     */
    public function getNumberOfDays()
    {
        $to = $this->time_to;
        $from = $this->time_from;
        return floor(($to - $from) / (60 * 60 * 24));
    }

    /**
     * Get the day price.
     *
     * @return int The day price.
     */
    public function getDayPrice()
    {
        return round($this->amount_item / $this->getNumberOfDays());
    }

    /**
     * Triggers a payin update request
     * @return bool
     */
    public function updateStatus()
    {
        $payin = Payin::findOne($this->payin_id);
        $payin->updateStatus();

        if ($this->status == self::AWAITING_PAYMENT && $payin->status == Payin::STATUS_PENDING) {
            $this->status = self::PENDING;
            $this->save();
        }
    }

}
