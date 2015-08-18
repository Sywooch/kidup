<?php

namespace app\components;
use Yii;
use yii\caching\ChainedDependency;
use yii\helpers\Json;
use yii\web\View;

class Cache extends \yii\base\Event
{
    public static function data($name, $exec, $time = 30, $dependency = null){
        if(YII_CACHE == false) return $exec();
        $name = debug_backtrace()[1]['function'].$name.\Yii::$app->language;
        $data = Yii::$app->cache->get($name);
        if ($data != false) {
            return $data;
        }
        $data = $exec();
        \Yii::$app->cache->set($name, $data, $time, $dependency);
        return $data;
    }

    public static function html($name, $exec, $dependencies = [], $doEcho = true){
        if(YII_CACHE == false) return $exec();
        $name = debug_backtrace()[1]['function'].$name;
        $dependencies['variations'][] = \Yii::$app->language;
        if($doEcho) {
            $view = new View();
            if($view->beginCache($name, $dependencies)){
                echo $exec();
                $view->endCache();
            }
            return false;
        }else{
            return self::data($name.Json::encode($dependencies), $exec);
        }
    }
}