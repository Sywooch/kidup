<?php

namespace app\components;
use Aws;
use yii\base\Exception;
use yii\helpers\Json;

abstract class Job
{
    private $queueName = '';

    private $maxAttempts = 10;

    abstract public function handle();

    public function __construct($data){
        if(is_object($data)){
            $data = Json::decode(Json::encode($data));
        }
        foreach ($data as $input => $value) {
            if(property_exists($this, $input)){
                $this->{$input} = $value;
            }else{
                throw new Exception("Not found in job");
            }
        }

        $this->queue();

        return $this;
    }

    private function queue(){

    }

    public function maxAttempts($count){
        $this->maxAttempts = $count;
        return $this;
    }
}


