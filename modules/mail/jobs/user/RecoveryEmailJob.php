<?php

namespace mail\jobs\user;

use app\extended\job\Job;
use mail\models\base\MailAccount;
use \mail\models\Mailer;
use \mail\models\Token;
use \user\models\User;
use yii\helpers\Url;

class RecoveryEmailJob extends Job{

    public $user_id;
    public $email;

    public function handle(){
        $user = User::find()->where(['id' => $this->user_id])->one();
        $token = new Token();
        $token->setAttributes([
            'user_id' => $user->id,
            'type' => Token::TYPE_RECOVERY,
        ]);
        $token->save();
        $url = $token->getUrl();

        return (new Mailer())->sendMessage([
            'email' => $user->email,
            'subject' => \Yii::t('mail.recovery.subject', 'KidUp recovery request'),
            'type' => Mailer::USER_RECOVERY,
            'params' => [
                'profileName' => $user->profile->first_name,
            ],
            'urls' => [
                'recovery' => $url,
            ]
        ]);
    }
}