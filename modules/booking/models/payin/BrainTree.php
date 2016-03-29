<?php

namespace booking\models\payin;

use Yii;
use yii\base\Model;
use yii\helpers\Json;

class BrainTreeError extends \app\extended\base\Exception
{
}

;

class BrainTreePaymentFailedException extends BrainTreeError
{
}

;

class BrainTreePayinAmountNotCorrect extends BrainTreeError
{
}

;

class BrainTree extends Model
{
    public $payin;
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

    public function __construct(Payin $payin = null)
    {
        $this->payin = $payin;

        return parent::__construct();
    }

    public function init()
    {
        \Braintree_Configuration::environment(\Yii::$app->keyStore->get('braintree_type'));
        \Braintree_Configuration::merchantId(\Yii::$app->keyStore->get('braintree_merchant_id'));
        \Braintree_Configuration::publicKey(\Yii::$app->keyStore->get('braintree_public_key'));
        \Braintree_Configuration::privateKey(\Yii::$app->keyStore->get('braintree_private_key'));
        return parent::init();
    }

    /**
     * Gets the braintree id of the braintree transaction
     * @return false|int
     */
    private function getBraintreeId()
    {
        if ($this->payin->braintree_backup == null) {
            return false;
        }
        $b = Json::decode($this->payin->braintree_backup);
        return $b['id'];
    }

    /**
     * Generates a client token to be used in the frontend
     * @return array
     */
    public function getClientToken()
    {
        return \Braintree_ClientToken::generate();
    }

    /**
     * Autorizes an braintree transaction
     * @return mixed
     * @throws BrainTreePayinAmountNotCorrect
     * @throws BrainTreePaymentFailedException
     */
    public function autorize()
    {
        if (!is_float($this->payin->amount) && !is_int($this->payin->amount)) {
            throw new BrainTreePayinAmountNotCorrect("Braintree payin amount not correct.");
        }
        $transaction = \Braintree_Transaction::sale(array(
            'amount' => $this->payin->amount,
            'paymentMethodNonce' => $this->payin->nonce,
            'options' => array(
                'submitForSettlement' => false
            ),
//            'merchantAccountId' => \Yii::$app->keyStore->get('braintree_merchant')
        ));
        if ($transaction->success === false) {
            throw new BrainTreePaymentFailedException($transaction->_attributes['message']);
        } else {
            return $transaction->transaction->_attributes;
        }
    }

    public function capture()
    {
        $res = \Braintree_Transaction::submitForSettlement($this->getBraintreeId());
        if ($res instanceof \Braintree_Result_Error) {
            throw new BrainTreeError($res->__toString());
        }
        $this->updateStatus();
    }

    /*
     * Release the autorized payment
     * @return bool
     */
    public function release()
    {
        return \Braintree_Transaction::void($this->getBraintreeId());
    }

    public function updateStatus()
    {
        if (!$this->getBraintreeId() && YII_ENV == 'test') {
            return false;
        }
        $b = \Braintree_Transaction::find($this->getBraintreeId());
        $this->payin->braintree_backup = Json::encode($b->_attributes);
        $this->payin->save();
        return $b->_attributes['status'];
    }

    /**
     * Testing method, faking accepting
     */
    public function sandboxSettlementAccept()
    {
        $this->payin->status = Payin::STATUS_ACCEPTED;
        $this->payin->save();
    }

    /**
     * Testing method, faking void
     */
    public function sandboxSettlementDecline()
    {
        $this->payin->status = Payin::STATUS_VOIDED;
        $this->payin->save();
    }
}