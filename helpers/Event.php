<?php

namespace app\helpers;

use app\extended\base\Exception;

class Event extends \yii\base\Event
{
    public $message;
    public $data;

    public static function trigger($obj, $trigger, $data = null){
        try{
            return \Yii::$app->trigger($obj->className().'-'. $trigger, new \yii\base\Event(['sender' => $obj]));
        }catch(Exception $e){

            \Yii::error("Triggering of event failed: ".$obj->className());
            return false;
        }
    }

    public static function register($classname, $trigger, $function){
        return \Yii::$app->on($classname.'-'.$trigger, $function);
    }
}