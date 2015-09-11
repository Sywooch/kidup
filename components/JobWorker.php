<?php

namespace app\components;

use app\models\base\JobQueue;
use yii\helpers\Json;

class JobWorker
{

    public function doJob()
    {
        $job = JobQueue::find()->where([
            'status' => Job::STATUS_IN_QUEUE,
        ])->andWhere('execution_time < :time')->params([':time' => time()])->one();
        if ($job !== null) {
            $jobQueue = $job;
            $jobData = Json::decode($job->data);
            $job = \Yii::createObject([
                'class' => $jobData['job_class'],
                'jobData' => $jobData['job_data'],
            ]);

            $res = $job->handle();

            if ($res == false) {
                $jobQueue->attempts++;
                $jobQueue->execution_time = time() + $job->retryPeriod;
                if ($jobQueue->attempts >= $job->maxAttempts) {
                    $jobQueue->status = Job::STATUS_FAILED;
                }
                $errors = Json::encode($job->getErrors());
                \Yii::error("Job failed: {$jobData['job_class']} {$errors}");
                $jobQueue->save();
            } else {
                $jobQueue->delete();
            }
        }
        return true;
    }

    private function getServerCpu(){
        $load = sys_getloadavg();
        return $load[0];
    }

    private function getServerMemory(){
        $free = shell_exec('free');
        $free = (string)trim($free);
        $free_arr = explode("\n", $free);
        $mem = explode(" ", $free_arr[1]);
        $mem = array_filter($mem);
        $mem = array_merge($mem);
        $memory_usage = $mem[2]/$mem[1]*100;

        return $memory_usage;
    }
}