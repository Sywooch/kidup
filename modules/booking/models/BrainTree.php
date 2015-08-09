<?php

namespace app\modules\booking\models;

use Yii;
use yii\base\Model;
use yii\helpers\Json;

class BrainTree extends Model
{
    public $payin;

    public function __construct(Payin $payin){
        $this->payin = $payin;

        return parent::__construct();
    }

    public function init()
    {
        \Braintree_Configuration::environment(\Yii::$app->keyStore->get('braintree_type'));
        \Braintree_Configuration::merchantId(   \Yii::$app->keyStore->get('braintree_merchant_id'));
        \Braintree_Configuration::publicKey(    \Yii::$app->keyStore->get('braintree_public_key'));
        \Braintree_Configuration::privateKey(   \Yii::$app->keyStore->get('braintree_private_key'));
        return parent::init();
    }

    private function getBraintreeId(){
        $b = Json::decode($this->payin->braintree_backup);
        return $b['id'];
    }

    public function getClientToken()
    {
        $clientToken = \Braintree_ClientToken::generate();
        return $clientToken;
    }

    public function autorize(Payin $payin){
        $transaction = \Braintree_Transaction::sale(array(
            'amount' => $payin->amount,
            'paymentMethodNonce' => $payin->nonce,
            'options' => array(
                'submitForSettlement' => false
            ),
            'merchantAccountId' => \Yii::$app->keyStore->get('braintree_merchant')
        ));
        if($transaction->success === false){
            if($transaction->_attributes['message'] == 'Cannot use a payment_method_nonce more than once.'){
                \Yii::$app->session->addFlash('error', \Yii::t('booking', 'Double transaction.'));
                return false;
            }else{
                \Yii::$app->session->addFlash('error', $transaction->_attributes['message']);
                return false;
            }
        }else{
            return $transaction->transaction->_attributes;
        }
    }

    public function capture(){
        $res = \Braintree_Transaction::submitForSettlement($this->getBraintreeId());
        return $res;
    }

    /*
     * Release the autorized payment
     */
    public function release(){
        $res = \Braintree_Transaction::void($this->getBraintreeId());
        return $res;
    }

    public function updateStatus(){
        $b = \Braintree_Transaction::find($this->getBraintreeId());
        $this->payin->braintree_backup = Json::encode($b->_attributes);
        $this->payin->save();
        return $b->_attributes['status'];
    }

    public function sandboxSettlementAccept(){
        \Braintree_TestHelper::settle($this->getBraintreeId());
    }

    public function sandboxSettlementDecline(){
        \Braintree_TestHelper::settlementDecline($this->getBraintreeId());
    }
}