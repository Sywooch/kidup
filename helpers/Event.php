<?php

namespace app\helpers;

use app\extended\base\Exception;

class Event extends \yii\base\Event
{
    public $message;
    public $data;
    public static $test_hooks = [];

    public static function trigger($obj, $trigger, $data = null)
    {
        if (is_string($obj)) {
            $classname = $obj;
        } else {
            $classname = $obj->className();
        }
        // Only use the last part of the class name
        if (strpos($classname, '\\') !== false) {
            $parts = explode('\\', $classname);
            $classname = $parts[count($parts) - 1];
        }

        // Mock a test event
        if (YII_ENV == 'test') {
            // Only use the last part of the class name
            if (strpos($classname, '\\') !== false) {
                $parts = explode('\\', $classname);
                $classname = $parts[count($parts) - 1];
            }

            if (array_key_exists($classname . '-' . $trigger, self::$test_hooks)) {
                $hooks = self::$test_hooks[$classname . '-' . $trigger];
                foreach ($hooks as $hook) {
                    $hook($obj);
                }
            }
        }

        try {
            return \Yii::$app->trigger($classname . '-' . $trigger, new \yii\base\Event(['sender' => $obj]));
        } catch (Exception $e) {
            \Yii::error("Triggering of event failed: " . $classname . '-' . $trigger);
            return false;
        }
    }

    public static function register($classname, $trigger, $function, $register_test_hook=false)
    {
        // Only use the last part of the class name
        if (strpos($classname, '\\') !== false) {
            $parts = explode('\\', $classname);
            $classname = $parts[count($parts) - 1];
        }

        // Save a test event
        if (YII_ENV == 'test' && $register_test_hook) {
            self::$test_hooks[$classname . '-' . $trigger][] = $function;
        }

        return \Yii::$app->on($classname . '-' . $trigger, $function);
    }

}

