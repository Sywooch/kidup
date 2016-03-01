<?php

namespace booking\models;

use app\helpers\Event;
use app\jobs\SlackJob;
use Carbon\Carbon;
use message\models\Conversation;
use user\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\ServerErrorHttpException;

/**
 * This is the model class for table "Booking".
 */
class BookingException extends \yii\base\ErrorException
{
}

class Booking extends base\Booking
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

    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            'init' => [
                'time_from',
                'time_to',
                'renter_id',
                'price',
                'status',
                'time_created',
                'item_id',
                'status',
                'promotion_code_id'
            ],
        ]);
    }

    public function ownerAccepts()
    {
        /**
         * @var Payin $payin
         */
        $payin = Payin::find()->where(['id' => $this->payin_id])->one();
        if ($payin->capture()) {
            $this->status = self::ACCEPTED;
            $this->save();
            Event::trigger($this, self::EVENT_OWNER_ACCEPTED);
            return true; // dont create payout yet, only when payin is successfull
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


    public function getConversationId()
    {
        $conv = Conversation::find()->where(['booking_id' => $this->id])->one();
        if ($conv == null) {
            return false;
        }
        return $conv->id;
    }

    public function getLocation($HTMLNewLines = false)
    {
        $newLine = PHP_EOL;
        if ($HTMLNewLines) {
            $newLine = '<br />';
        }
        return $this->item->location->street_name . ' ' . $this->item->location->street_number . ',' . $newLine .
        $this->item->location->zip_code . ' ' . $this->item->location->city . $newLine . ', ' . $newLine .
        $this->item->location->country0->name;
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

    public function setExpireDate()
    {
        $expireDate = time() + 6 * 24 * 60 * 60;
        if ($this->time_to < $expireDate && $this->time_to > time() - 24 * 60 * 60) {
            $expireDate = $this->time_to - 24 * 60 * 60;
        } else {
            if ($this->time_to < $expireDate) {
                $expireDate = $this->time_to - 5 * 60; // 5 min
            }
        }
        $this->request_expires_at = $expireDate;
    }

    public function startConversation($message)
    {
        if ($this->conversation !== null) {
            if (count($this->conversation->messages) == 0) {
                return $this->conversation->addMessage($message, $this->item->owner_id, \Yii::$app->user->id);
            }
            return true;
        }
        $c = new Conversation();
        $c->initiater_user_id = Yii::$app->user->id;
        $c->target_user_id = $this->item->owner_id;
        $c->title = $this->item->name;
        $c->booking_id = $this->id;
        $c->created_at = time();
        if (!$c->save()) {
            throw new ServerErrorHttpException('Conversation couldn\'t be saved' . Json::encode($c->getErrors()));
        }

        if ($message == '' || $message == null) {
            $message = \Yii::t('booking.create.automated_new_message',
                'This is an automated message from KidUp: in this conversation you can for example chat about the product and exchange.');
        }

        $messageBool = $c->addMessage($message, $this->item->owner_id, \Yii::$app->user->id);
        return $messageBool;
    }

    public function hasBookinger($id)
    {
        if ($this->renter_id !== $id) {
            throw new ForbiddenHttpException("You are not the renter");
        }

        return $this;
    }

    public function hasStatus($statusId)
    {
        if ($this->status !== $statusId) {
            throw new BadRequestHttpException("This action cannot be performed when the rent is in this status");
        }

        return $this;
    }

    public function setPayinPrices()
    {
        $prices = $this->item->getPriceForPeriod($this->time_from, $this->time_to, $this->currency);

        $this->amount_item = $prices['price'];
        $this->amount_payin = $prices['total'];
        $this->amount_payin_fee = round($prices['_detailed']['fee'], 4);
        $this->amount_payin_fee_tax = round($prices['_detailed']['feeTax'], 4);
        $this->amount_payin_costs = $this->amount_payin * 0.028 + 1.25; // todo make this dynamic

        $payoutFee = \Yii::$app->params['payoutServiceFeePercentage'] * $prices['price'];
        $payoutFeeTax = $payoutFee * 0.25; // static tax for now
        $this->amount_payout = round($this->amount_item - $payoutFee - $payoutFeeTax);
        $this->amount_payout_fee = round($payoutFee, 4);
        $this->amount_payout_fee_tax = round($payoutFeeTax, 4);

        return $this;
    }


    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => function () {
                    return Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp;
                }
            ],
        ];
    }
}
