<?php

namespace app\components;

use yii\base\Component;

class PhoneTextSendException extends \yii\base\Exception{};

class PhoneTexter extends Component
{
    public static function text($code, $phoneNumber){
        $message = urlencode('KidUp code ' . $code);
        $key = Yii::$app->keyStore->get('nexmo_api_key');
        $secret = Yii::$app->keyStore->get('nexmo_api_secret');
        // Create a client with a base URI
        $client = new \GuzzleHttp\Client();
        $url = 'https://rest.nexmo.com/sms/json?'.
            'api_key=' . $key .
            '&api_secret=' . $secret .
            '&from=KidUp&to=+' .
            $phoneNumber .
            '&text=' . $message;
        $res = json_decode($client->post($url));
        if (isset($res->messages[0]->{"error-text"})) {
            throw new PhoneTextSendException($res->messages[0]->{"error-text"});
        }
        return true;
    }
}