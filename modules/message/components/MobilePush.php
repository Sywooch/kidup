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
        if (!array_key_exists($platform, $this->arns)) {
            $endpointArn = 'unknown_platform';
        } else {
            $result = $this->sns->createPlatformEndpoint(array(
                // PlatformApplicationArn is required
                'PlatformApplicationArn' => $this->arns[$platform],
                // Token is required
                'Token' => $token
            ));
            $endpointArn = $result['EndpointArn'];
        }

        $device = new MobileDevices();
        $device->token = $token;
        $device->platform = $platform;
        $device->device_id = $device_id;
        $device->is_subscribed = true;
        $device->endpoint_arn = $endpointArn;
        return $device->save();
    }

    /**
     * Send a message to a device.
     */
    public function sendMessage($arn, $message, $parameters = [], $title='KidUp app') {
        $result = $this->sns->publish([
            'TargetArn' => $arn,
            'MessageStructure' => 'json',
            'Message' => json_encode([
                'default' => $message,
                'APNS' => json_encode([
                    'aps' => array_merge([
                        'alert' => $message,
                        'sound' => 'default',
                    ], $parameters)
                ]),
                'GCM' => json_encode([
                    'data' => array_merge([
                        'title' => $title,
                        'message' => $message,
                    ], $parameters)
                ]),
            ])
        ]);
        print_r($result);
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
