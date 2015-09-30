<?php

namespace app\commands;

use Yii;
use yii\console\Controller;

class CronController extends Controller
{
    private $modules = [
        'booking', 'home', 'item', 'review', 'mail'
    ];

    private function findModuleController($module){
        $className =  "app\\modules\\".$module."\\controllers\\CronController";
        if(Yii::$app->hasModule($module) && class_exists($className)){
            $controller = new $className();
            return $controller;
        }else{
            return false;
        }
    }
    public function actionMinute()
    {
        foreach($this->modules as $m){
            if($controller = $this->findModuleController($m)){
                if(method_exists($controller, 'minute')) $controller->minute();
            }
        }
    }

    public function actionHour()
    {
        foreach($this->modules as $m){
            if($controller = $this->findModuleController($m)){
                if(method_exists($controller, 'hour')) $controller->hour();
            }
        }
    }

    public function actionDay()
    {
        foreach($this->modules as $m){
            if($controller = $this->findModuleController($m)){
                if(method_exists($controller, 'day')) $controller->day();
            }
        }
    }
}
