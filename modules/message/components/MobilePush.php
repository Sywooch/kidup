<?php
namespace message\components;

use message\models\base\MobileDevices;
use Aws;

class MobilePush {

    private $sns;
    private $arns;

    /**
     * MobilePush constructor which initializes all required services.
     */
    public function __construct() {
        $aws = new Aws\Sdk([
            'credentials' => [
                'key' => "AKIAJZ4OT43S5DIZPMYQ",
                'secret' => "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"
            ],
            'region' => 'eu-central-1',
            'version' => 'latest'
        ]);
        $this->sns = $aws->createSns();

        $this->arns = [];
        $apps = $this->sns->listPlatformApplications();
        foreach ($apps['PlatformApplications'] as $app) {
            $arn = $app['PlatformApplicationArn'];
            if (strpos($arn, 'Apple') !== false) {
                $this->arns['ios'] = $arn;
            } elseif (strpos($arn, 'Android') !== false) {
                $this->arns['android'] = $arn;
            }
        }
    }

    /**
     * Register a device.
     */
    public function registerDevice($device_id, $token, $platform) {
        if (!array_key_exists($platform, $this->arns)) return false;
        $result = $this->sns->createPlatformEndpoint(array(
            // PlatformApplicationArn is required
            'PlatformApplicationArn' => $this->arns[$platform],
            // Token is required
            'Token' => $token
        ));

        $device = new MobileDevices();
        $device->token = $token;
        $device->platform = $platform;
        $device->device_id = $device_id;
        $device->is_subscribed = true;
        $device->endpoint_arn = $result['EndpointArn'];
        $device->save();
    }

    /**
     * Send a message to a device.
     */
    public function sendMessage() {
        $this->platform->sendMessage();
    }

    public function getTopics() {
        $result = [];
        $topics = $this->sns->listTopics();
        foreach ($topics['Topics'] as $topic) {
            $data = $this->sns->getTopicAttributes($topic);
            $result[] = $data['Attributes'];
        }
        return $result;
    }

    public function subscribeToTopics($endpointArn, $topics) {
        foreach ($topics as $topic) {
            $this->sns->subscribe([
                'TopicArn' => $topic,
                'Protocol' => 'application',
                'Endpoint' => $endpointArn
            ]);
        }
    }

}
