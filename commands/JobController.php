<?php

namespace app\commands;

use app\modules\admin\jobs\TestJob;
use Yii;
use yii\console\Controller;

class JobController extends Controller
{

    public function actionDoJob(){
        (new \app\components\JobWorker())->doJob();
    }

    public function actionTest(){
        new TestJob([
            'user_id' => 1,
            'code' => rand(0,1111111)
        ]);
    }
}
