<?php

namespace app\commands;

use admin\jobs\SlackJob;
use Yii;
use yii\console\Controller;

class JobController extends Controller
{
    /**
     * The actual worker, managed by the minute cron which checks every min if its still up, and starts it if its not
     */
    public function actionWorker()
    {
        while (true) {
            @(new \app\components\jobs\JobWorker())->doJob();
            sleep(1);
        }
    }

    public function actionTest()
    {
        new SlackJob([
            'message' => 'test from terminal',
        ]);
    }
}
