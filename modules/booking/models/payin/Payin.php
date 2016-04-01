<?php

namespace booking\models\payin;

use app\helpers\Event;
use booking\models\invoice\InvoiceFactory;
use booking\models\payout\PayoutFactory;
use Carbon\Carbon;
use Yii;
use app\extended\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * This is the model class for table "payin".
 */
class PayinException extends \app\extended\base\Exception
{
}

class Payin extends PayinBase
{
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

    /**
     * Authorizes the payment
     * @return BrainTreeError|bool|\Exception
     */
    public function authorize()
    {
        $brainTree = new BrainTree($this);
        try {
            $transaction = $brainTree->autorize();
        } catch (BrainTreeError $e) {
            throw new PayinException($e->getMessage(), null, $e);
        }
        $this->status = $this->brainTreeToPayinStatus($transaction['status']);
        $this->braintree_backup = Json::encode($transaction);
        return $this->save();
    }

    /**
     * Updates the status of the payment
     * @return bool
     */
    public function updateStatus()
    {
        $status = new BrainTree($this);
        $this->status = $this->brainTreeToPayinStatus($status->updateStatus());
        return $this->save();
    }

    public function beforeSave($insert)
    {
        if ($this->isAttributeChanged('status')) {
            $this->onStatusChange();
        }
        return parent::beforeSave($insert);
    }

    /**
     * Processes changes in status of the payin and triggers corresponding events
     */
    private function onStatusChange()
    {
        if ($this->status == self::STATUS_ACCEPTED && $this->invoice_id == null && $this->booking !== null) {
            $this->invoice_id = (new InvoiceFactory)->createForBooking($this->booking)->id;
            $this->save();
            Event::trigger($this, self::EVENT_PAYIN_CONFIRMED);
            (new PayoutFactory())->createFromBooking($this->booking);
        }

        if ($this->status == self::STATUS_AUTHORIZED) {
            Event::trigger($this, self::EVENT_AUTHORIZATION_CONFIRMED);
        }
        if ($this->status == self::STATUS_FAILED) {
            Event::trigger($this, self::EVENT_FAILED);
        }
    }

    /**
     * Settle the payin, called when booking is confirmed
     */
    public function capture()
    {
        $brainTree = new BrainTree($this);
        try {
            if (YII_ENV == 'test') {
                $brainTree->sandboxSettlementAccept();
            } else {
                $brainTree->capture();
            }
        } catch (BrainTreeError $e) {
            throw new PayinException("Braintree capture failed", null, $e);
        }

        return $this->updateStatus();
    }

    /**
     * Release the payin, called when booking is declined or not responded to
     */
    public function release()
    {
        $brainTree = new BrainTree($this);
        try {
            if (YII_ENV == 'test') {
                $brainTree->sandboxSettlementDecline();
            } else {
                $brainTree->release();
            }
        } catch (BrainTreeError $e) {
            throw new PayinException("Braintree release failed", null, $e);
        }
        return $this->updateStatus();
    }

    /**
     * Maps a status from braintree to a payin status
     * @param $status
     * @return bool|string
     */
    private function brainTreeToPayinStatus($status)
    {
        $statusses = [
            BrainTree::AUTHORIZING => self::STATUS_PENDING,
            BrainTree::AUTHORIZED => self::STATUS_AUTHORIZED,
            BrainTree::GATEWAY_REJECTED => self::STATUS_FAILED,
            BrainTree::FAILED => self::STATUS_FAILED,
            BrainTree::SETTLEMENT_DECLINED => self::STATUS_FAILED,
            BrainTree::SETTLING => self::STATUS_SETTLING,
            BrainTree::SUBMITTED_FOR_SETTLEMENT => self::STATUS_SETTLING,
            BrainTree::SETTLED => self::STATUS_ACCEPTED,
            BrainTree::VOIDED => self::STATUS_VOIDED
        ];

        return isset($statusses[$status]) ? $statusses[$status] : $status;
    }

}
