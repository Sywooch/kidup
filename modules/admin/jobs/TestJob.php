<?php

namespace admin\jobs;

use app\extended\job\Job;
use notifications\models\Token;

class TestJob extends Job{

    public $user_id;
    public $code;

    public $maxAttempts = 3;

    public function handle(){
        $token = new Token();
        $token->setAttributes([
            'user_id' => $this->user_id,
            'code' => $this->code,
            'type' => 2
        ]);
        if(true){
            $this->addError('code', 'code not correct');
            return false;
        }
        return true;
    }

}