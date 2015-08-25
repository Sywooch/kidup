<?php

namespace app\modules\booking\models;

use app\components\Event;
use Carbon\Carbon;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * This is the model class for table "payin".
 */
class Payin extends \app\models\base\Payin
{
    // Transaction Status
    const AUTHORIZATION_EXPIRED = 'authorization_expired';
    const AUTHORIZING = 'authorizing';
    const AUTHORIZED = 'authorized';
    const GATEWAY_REJECTED = 'gateway_rejected';
    const FAILED = 'failed';
    const PROCESSOR_DECLINED = 'processor_declined';
    const SETTLED = 'settled';
    const SETTLING = 'settling';
    const SUBMITTED_FOR_SETTLEMENT = 'submitted_for_settlement';
    const VOIDED = 'voided';
    const UNRECOGNIZED = 'unrecognized';
    const SETTLEMENT_DECLINED = 'settlement_declined';
    const SETTLEMENT_PENDING = 'settlement_pending';

    const STATUS_INIT = 'init';
    const STATUS_PENDING = 'status_pending';
    const STATUS_AUTHORIZED = 'status_authorized';
    const STATUS_FAILED = 'status_failed';
    const STATUS_SETTLING = 'status_settling';
    const STATUS_ACCEPTED = 'status_accepted';
    const STATUS_VOIDED = 'status_voided';

    const EVENT_PAYIN_CONFIRMED = 'event_payin_confirmed';
    const EVENT_AUTHORIZATION_CONFIRMED = 'event_authorization_confirmed';
    const EVENT_FAILED = 'event_failed';

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

    public function authorize()
    {
        $brainTree = new BrainTree($this);
        $transaction = $brainTree->autorize($this);
        if ($transaction === false) {
            // something went wrong
            $this->nonce = null;
            $this->save();
            return false;
        }
        $this->status = $this->brainTreeToPayinStatus($transaction['status']);
        $this->braintree_backup = Json::encode($transaction);
        return $this->save();
    }

    public function updateStatus()
    {
        $status = new BrainTree($this);
        $status = $this->brainTreeToPayinStatus($status->updateStatus());
        if ($status !== $this->status) {
            $this->status = $status;
            $this->save();
        }
    }

    public function beforeSave($insert)
    {
        if ($this->isAttributeChanged('status')) {
            if ($this->status == self::STATUS_ACCEPTED && $this->invoice_id == null) {
                $invoice = new Invoice();
                $invoice = $invoice->create($this->booking);
                $this->invoice_id = $invoice->id;
                if ($this->save()) {
                    Event::trigger($this, self::EVENT_PAYIN_CONFIRMED);
                }
                (new Payout())->createFromBooking($this->booking);
            }
            if ($this->status == self::STATUS_AUTHORIZED) {
                /**
                 * @var Booking $b
                 */
                $b = Booking::findOne($this->bookings[0]->id);
                if ($b->conversation == null) {
                    $this->status = self::STATUS_PENDING;
                    return parent::beforeSave($insert); // the booking did not initiate yet, so let the cron take this one in one minute
                }
                Event::trigger($this, self::EVENT_AUTHORIZATION_CONFIRMED);
            }
            if ($this->status == self::STATUS_FAILED) {
                Event::trigger($this, self::EVENT_FAILED);
            }
        }
        return parent::beforeSave($insert);
    }

    /**
     * Settle the payin, called when booking is confirmed
     */
    public function capture()
    {
        $brainTree = new BrainTree($this);
        $brainTree->capture();
        $this->updateStatus();
        return true;
    }

    /**
     * Release the payin, called when booking is declined or not responded to
     */
    public function release()
    {
        $brainTree = new BrainTree($this);
        $brainTree->release();
        $this->updateStatus();
        return true;
    }

    private function brainTreeToPayinStatus($status)
    {
        if (in_array($status, [self::AUTHORIZING])) {
            return self::STATUS_PENDING;
        }
        if (in_array($status, [self::AUTHORIZED])) {
            return self::STATUS_AUTHORIZED;
        }
        if (in_array($status, [self::GATEWAY_REJECTED, self::FAILED, self::SETTLEMENT_DECLINED])) {
            return self::STATUS_FAILED;
        }
        if (in_array($status, [self::SETTLING, self::SUBMITTED_FOR_SETTLEMENT])) {
            return self::STATUS_SETTLING;
        }
        if (in_array($status, [self::SETTLED])) {
            return self::STATUS_ACCEPTED;
        }
        if (in_array($status, [self::VOIDED])) {
            return self::STATUS_VOIDED;
        }
        return false;
    }

    public function getBooking()
    {
        return $this->hasOne(Booking::className(), ['payin_id' => 'id']);
    }
}
