<?php

namespace app\components;

class Event extends \yii\base\Event
{
    public $message;
    public $data;

    public static function trigger($obj, $trigger, $data = null){
        return \Yii::$app->trigger($obj->className().'-'. $trigger, new \yii\base\Event(['sender' => $obj]));
    }

    public static function register($classname, $trigger, $function){
        return \Yii::$app->on($classname.'-'.$trigger, $function);
    }
}