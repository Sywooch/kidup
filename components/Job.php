<?php

namespace app\components;

use app\models\base\JobQueue;
use Aws;
use yii\base\Exception;
use yii\base\Model;
use yii\helpers\Json;

abstract class Job extends Model
{
    const STATUS_IN_QUEUE = 0;
    const STATUS_FAILED = 9;

    public $jobError;

    private $queueName = 'default';
    private $data = [];
    private $isWorker = false;

    private $jobData = false;
    public $maxAttempts = 10;
    public $retryPeriod = 10;

    abstract public function handle();

    public function __construct($data = [])
    {
        if(isset($data['jobData'])){
            $this->isWorker = true;
            $this->jobData = $data['jobData'];
        }else{
            $this->jobData = $data;
        }

        foreach ($this->jobData as $input => $value) {
            if (property_exists($this, $input)) {
                $this->{$input} = $value;
            } else {
                throw new Exception("Not found in job");
            }
        }
        if($this->isWorker){
            return $this;
        }
//        parent::__construct();

        return $this->queue();
    }

    private function set()
    {
        $this->data = [
            'job_class' => $this::className(),
            'job_data' => $this->jobData
        ];
        $jq = new JobQueue();
        $jq->setAttributes([
            'data' => Json::encode($this->data),
            'queue' => $this->queueName,
            'attempts' => 0,
            'status' => self::STATUS_IN_QUEUE,
            'created_at' => time(),
            'execution_time' => time() - 1
        ]);
        return $jq->save();
    }

    protected function queue()
    {
        $use = \Yii::$app->keyStore->get('use_queue');

        if($use){
            $this->set();
        }else{
            $this->handle();
        }
        return $this;
    }
}


