<?php

namespace app\components;
use Yii;
use yii\caching\ChainedDependency;

class Cache extends \yii\base\Event
{
    public static function data($name, $exec, $time = 30, $dependency = null){
        if(YII_CACHE == false) return $exec();
        $name = debug_backtrace()[1]['function'].$name;
        $data = Yii::$app->cache->get($name);
        if ($data != false) {
            return $data;
        }
        $data = $exec();
        \Yii::$app->cache->set($name, $data, $time, $dependency);
        return $exec();
    }
}