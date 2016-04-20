<?php

namespace notification\jobs\user;

use app\components\jobs\Job;
use notification\models\Mailer;
use user\models\token\Token;
use user\models\user\User;

class RecoveryEmailJob extends Job{

    public $user_id;
    public $email;

    // TODO what does this do?

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