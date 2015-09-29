<?php

namespace app\jobs;

use app\extended\job\Job;
use yii\helpers\Json;

class SlackJob extends Job{

    public $maxAttempts = 3;

    /**
     * @var $message
     * Message to be sent to slack
     */
    public $message;

    private $client;

    public function handle(){
        $settings = [
            'username' => 'Simon',
            'link_names' => true
        ];

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