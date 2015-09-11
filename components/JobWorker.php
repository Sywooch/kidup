<?php

namespace app\components;

use app\models\base\JobQueue;
use yii\helpers\Json;

class JobWorker
{

    private $jobQueue;

    /**
     * @return bool|Job
     * @throws \yii\base\InvalidConfigException
     */
    private function getNextJob()
    {
        $job = JobQueue::find()->where([
            'status' => Job::STATUS_IN_QUEUE,
        ])->andWhere('execution_time < :time')->params([':time' => time()])->one();
        if ($job !== null) {
            $job->data = Json::decode($job->data);
            $this->jobQueue = $job;
            return \Yii::createObject([
                'class' => $job->data['job_class'],
                'jobData' => $job->data['job_data'],
            ]);
        }
        return false;
    }

    public function doJob()
    {
        $job = $this->getNextJob();
        if ($job !== false) {
            $res = $job->handle();
            /**
             * @var JobQueue $jq
             */
            $jq = JobQueue::findOne($this->jobQueue->id);
            if ($res == false) {
                $jq->id = $this->jobQueue->id;
                $jq->attempts++;
                $jq->execution_time = time() + $job->retryPeriod;
                if ($jq->attempts >= $job->maxAttempts) {
                    $jq->status = Job::STATUS_FAILED;
                }
                $jq->data = Json::encode($jq->data);
//                $jq->data['_errors'][$jq->attempts] = $job->getErrors();
                $jq->save();
            } else {
                $jq->delete();
            }
        }
        return true;
    }
}