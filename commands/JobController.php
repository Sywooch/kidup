<?php

namespace app\commands;

use app\jobs\SlackJob;
use Yii;
use yii\console\Controller;

class JobController extends Controller
{
    public function actionWorker()
    {
        while (true) {
            $performedJob = @(new \app\extended\job\JobWorker())->doJob();
            if ($performedJob == null) {
                sleep(2);
            }
            echo 1;
        }
    }

    public function actionTest()
    {
        new SlackJob([
            'message' => 'test from terminal',
        ]);
    }

    public function actionShellStart()
    {
        $shellScript = shell_exec("ps aux | grep 'php yii job/worker'");
        $found = false;

        foreach (explode(PHP_EOL, $shellScript) as $line) {
            if(strpos($line, 'grep') < 0 && strlen($line) > 10){
                $found = true;
            }
        }
        if(!$found){
            $res = shell_exec("cd ".Yii::$aliases['@app']." && php yii job/worker > /dev/null 2>&1");
        }
    }
}
