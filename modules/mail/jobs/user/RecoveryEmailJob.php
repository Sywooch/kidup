<?php

namespace app\modules\mail\jobs\user;

use app\extended\job\Job;
use app\models\base\MailAccount;
use app\modules\mail\models\Mailer;
use app\modules\mail\models\Token;
use app\modules\user\models\User;
use yii\helpers\Url;

class RecoveryEmailJob extends Job{

    public $user_id;
    public $email;

    public function handle(){
        $user = User::findOne($this->user_id);
        $token = new Token();
        $token->setAttributes([
            'user_id' => $user->id,
            'type' => Token::TYPE_RECOVERY,
        ]);
        $token->save();
        $url = $token->getUrl();

        return (new Mailer())->sendMessage([
            'email' => $user->email,
            'subject' => \Yii::t('mail', 'KidUp recovery request'),
            'type' => Mailer::USER_RECOVERY,
            'params' => [
                'profileName' => $user->profile->first_name,
            ],
            'urls' => [
                'recovery' => $url,
            ]
        ]);
    }

    private function test(){
        new RecoveryEmailJob([
            'user_id' => 1,
            'email' => 'simpi_123@hotmail.com'
        ]);
    }

}