<?php

namespace app\jobs;

use app\extended\job\Job;
use yii\helpers\Json;

class SlackJob extends Job{

    /**
     * @var $message
     * Message to be sent to slack
     */
    public $message;

    private $client;

    public function init(){
        $this->maxAttempts = 2000;
        $this->retryPeriod = 10;
        parent::init();
    }

    public function handle(){
        $settings = [
            'username' => 'Simon',
            'link_names' => true
        ];

//        if(YII_ENV !== 'prod'){
//            return true;
//        }

        $slackUrl = \Yii::$app->keyStore->get('slack_service_url');

        $this->client = new \Maknz\Slack\Client('https://hooks.slack.com/services/'.$slackUrl, $settings);

        try{
            $this->client->send($this->message);
        }catch (\GuzzleHttp\Exception\ServerException $e){
            // todo this does not catch all errors yet
            $this->addError('code', Json::encode($e));
            return false;
        }
        return true;
    }
}