<?php

namespace app\commands;

use app\jobs\SlackJob;
use app\modules\admin\jobs\TestJob;
use Yii;
use yii\console\Controller;

class JobController extends Controller
{

    public function actionDoJob(){
        while(true){
            $performedJob = @(new \app\extended\job\JobWorker())->doJob();
            if($performedJob == null){
                sleep(2);
            }
            echo 1;
        }
    }

    public function actionTest(){
        new SlackJob([
            'message' => 'test from terminal',
        ]);
    }
}
