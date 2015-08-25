<?php

namespace app\components;
use Yii;
use yii\helpers\Json;
use yii\web\View;

class Cache extends \yii\base\Event
{
    public static function data($name, $exec, $time = 30, $dependency = null){
        if(YII_CACHE == false) return $exec();
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
        $dependencies['variations'][] = \Yii::$app->language;
        $view = new View();
        if($doEcho) {
            if($view->beginCache($name, $dependencies)){
                echo $exec();
                $view->endCache();
            }
            return false;
        }else{
            if($view->beginCache($name, $dependencies)){
                $data = $exec();
                $view->endCache();
                return $data;
            }
            return $exec();
        }
    }

    public static function remove($regexp){
        $toDelete = new \APCIterator('user', '/^'.$regexp.'/', APC_ITER_VALUE);

        var_dump( apc_delete($toDelete) );
    }
}