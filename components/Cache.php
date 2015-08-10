<?php

namespace app\components;
use Yii;
class Cache extends \yii\base\Event
{
    public static function data($name, $exec){
        if(YII_CACHE == false) return $exec();
        $name = debug_backtrace()[1]['function'].$name;
        $data = Yii::$app->cache->get($name);
        if ($data != false) {
            return $data;
        }
        $data = $exec();
        Yii::$app->cache->set($name, $data);
        return $exec();
    }
}