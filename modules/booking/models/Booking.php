<?php

namespace app\modules\booking\models;

use app\components\Event;
use app\modules\message\models\Conversation;
use Carbon\Carbon;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\web\ServerErrorHttpException;

/**
 * This is the model class for table "Booking".
 */
class Booking extends \app\models\base\Booking
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
            'init' => ['time_from', 'time_to', 'renter_id', 'price', 'status', 'time_created', 'item_id', 'status'],
        ]);
    }

    public function ownerAccepts()
    {
        $payin = Payin::findOne($this->payin_id);
        if ($payin->capture()) {
            $this->status = self::ACCEPTED;
            $this->save();
            Event::trigger($this, self::EVENT_OWNER_ACCEPTED);
            $payout = new Payout();

            return true; // dont create payout yet, only when payin is successfull
        }

        return false;
    }

    public function ownerDeclines()
    {
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
        }

        return false;
    }

    public function getStatusName()
    {
        if ($this->status == self::AWAITING_PAYMENT) {
            return \Yii::t('app', 'Awaiting payment');
        }
        if ($this->status == self::PENDING) {
            return \Yii::t('app', 'Pending');
        }
        if ($this->status == self::NO_RESPONSE) {
            return \Yii::t('app', 'Refused');
        }
        if ($this->status == self::DECLINED) {
            return \Yii::t('app', 'Refused');
        }
        if ($this->status == self::ACCEPTED) {
            return \Yii::t('app', 'Accepted');
        }
        if ($this->status == self::CANCELLED) {
            return \Yii::t('app', 'Cancelled');
        }

        return false;
    }

    public function getConversation()
    {
        return $this->hasOne(\app\models\base\Conversation::className(), ['booking_id' => 'id']);
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

    /**
     * Make sure all the correct emails are send if the status changes
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->isAttributeChanged('status')) {

        }

        return parent::beforeSave($insert);
    }

    public function startConversation($message)
    {
        $c = new Conversation();
        $c->initiater_user_id = Yii::$app->user->id;
        $c->target_user_id = $this->item->owner_id;
        $c->title = $this->item->name;
        $c->booking_id = $this->id;
        $c->created_at = time();
        if (!$c->save()) {
            throw new ServerErrorHttpException('Conversation couldnt be saved' . Json::encode($c->getErrors()));
        }

        if ($message == '' || $message == null) {
            $message = \Yii::t('booking', 'This is an automated message from KidUp.');
        }

        return $c->addMessage($message, $this->item->owner_id, \Yii::$app->user->id);
    }

    public function hasBookinger($id)
    {
        if ($this->renter_id !== $id) {
            new Error("You are not the renter", Error::FORBIDDEN);
        }

        return $this;
    }

    public function hasStatus($statusId)
    {
        if ($this->status !== $statusId) {
            return \Yii::$app->error->badRequest("This action cannot be performed when the rent is in this status");
        }

        return $this;
    }


    public function setPayinPrices($timestampFrom, $timestampTo, $prices)
    {
        $days = floor(($timestampTo - $timestampFrom) / (60 * 60 * 24));

        $dailyPrices = [
            'day' => $prices['day'],
            'week' => $prices['week'] / 7,
            'month' => $prices['month'] / 30,
        ];
        if ($days <= 7) {
            $price = $dailyPrices['day'] > 0 ? $days * $dailyPrices['day'] : $days * $dailyPrices['week'];
        } elseif ($days > 7 && $days <= 31) {
            $price = $dailyPrices['week'] * $days;
        } else {
            $price = $dailyPrices['month'] > 0 ? $days * $dailyPrices['month'] : $days * $dailyPrices['week'];
        }

        $payinFee = \Yii::$app->params['payinServiceFeePercentage'] * $price;
        $payinFeeTax = $payinFee * 0.25; // static tax for now

        $this->amount_item = round($price);
        $this->amount_payin = round($price + $payinFee + $payinFeeTax);
        $this->amount_payin_fee = round($payinFee, 4);
        $this->amount_payin_fee_tax = round($payinFeeTax, 4);
        $this->amount_payin_costs = $this->amount_payin * 0.028 + 1.25; // todo make this dynamic

        $payoutFee = \Yii::$app->params['payoutServiceFeePercentage'] * $price;
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
