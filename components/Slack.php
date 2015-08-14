<?php

namespace app\components;
use yii\base\Component;
use Yii;
class Slack extends Component
{
    private $client;

    public function init(){
        $settings = [
            'username' => 'Simon',
            'link_names' => true
        ];



        $this->client = new \Maknz\Slack\Client('https://hooks.slack.com/services/'.Yii::$app->keyStore->get('slack_service_url'), $settings);
        return parent::init();
    }

    public function send($message){
        try{
            $this->client->send($message);
        }catch (\GuzzleHttp\Exception\ServerException $e){
            // whoops?
        }
        return false;
    }
}