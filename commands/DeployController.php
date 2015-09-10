<?php

namespace app\commands;

use Yii;
use yii\console\Controller;

class DeployController extends Controller
{
    public function actionAfterDeploy($buildNum = null){
        // clear all cache
        \Yii::$app->cache->flush();
        // new buildnumber
        $fileName = Yii::$aliases['@runtime'].'/buildnum';
        if(!is_file($fileName)){
            $buildNum = date("YY.MM").'.1';
        }
    }
}
