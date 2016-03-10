<?php

namespace admin\controllers;

use app\helpers\Event;
use user\models\User;

class TestController extends Controller
{

    public function actionMail(){
        $user = User::findOne(['email' => 'simon@tuple.nl']);
        Event::trigger($user, User::EVENT_USER_REGISTER_DONE);
    }

    public function actionTest(){
        $http = new \Braintree_HttpClientApi(\Braintree_Configuration::$global);
        $nonce = $http->nonce_for_new_card([
            "creditCard" => [
                "number" => "4111111111111111",
                "expirationMonth" => "11",
                "expirationYear" => "2099"
            ],
            "share" => true
        ]);
        $gateway = new \Braintree_Gateway([
            'environment' => 'development',
            'merchantId' => 'integration_merchant_id',
            'publicKey' => 'integration_public_key',
            'privateKey' => 'integration_private_key'
        ]);
        $result = $gateway->transaction()->sale([
            'amount' => '47.00',
            'paymentMethodNonce' => $nonce
        ]);

        \yii\helpers\VarDumper::dump($result,10,true); exit();
    }
}
