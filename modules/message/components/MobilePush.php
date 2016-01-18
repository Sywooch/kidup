<?php
namespace message\components;

use message\models\base\Notification;

class MobilePush {

    /** @var PushPlatform Any push platform. */
    private $platform;

    /**
     * MobilePush constructor which initializes all required services.
     */
    public function __construct() {
        $this->platform = new AmazonSNS();
    }

    /**
     * Register a device.
     */
    public function registerDevice($device_id) {
        $device = new Notification();
        $device->platform = Notification::PLATFORM_GCM;
        $device->token = '123';
        $device->device_id = $device_id;
        $device->save();

        $this->platform->registerDevice();
    }

    /**
     * Send a message to a device.
     */
    public function sendMessage() {
        $this->platform->sendMessage();
    }

}
