<?php

namespace app\helpers;

use app\extended\base\Exception;

class Event extends \yii\base\Event
{
    public $message;
    public $data;

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

        try {
            return \Yii::$app->trigger($classname . '-' . $trigger, new \yii\base\Event(['sender' => $obj]));
        } catch (Exception $e) {
            \Yii::error("Triggering of event failed: " . $classname . '-' . $trigger);
            return false;
        }
    }

    public static function register($classname, $trigger, $function)
    {
        // Only use the last part of the class name
        if (strpos($classname, '\\') !== false) {
            $parts = explode('\\', $classname);
            $classname = $parts[count($parts) - 1];
        }

        return \Yii::$app->on($classname . '-' . $trigger, $function);
    }

}

