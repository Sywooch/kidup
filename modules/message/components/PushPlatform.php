<?php
namespace message\components;

abstract class PushPlatform {

    /**
     * Register a device at a push platform.
     */
    public abstract function registerDevice();

    /**
     * Send a message to a device using a push platform.
     */
    public abstract function sendMessage();

}
