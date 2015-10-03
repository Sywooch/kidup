<?php
namespace mail\mails;

use \mail\models\Mailer;
use \mail\models\Token;
use Yii;
use yii\helpers\Url;

class User extends Mailer
{
    /**
     * Welcome email after signup
     * @param $data
     * @return bool
     */
    public function welcome($data)
    {
        $user = $data;
        $token = new Token();
        $token->setAttributes([
            'user_id' => $user->id,
            'type' => Token::TYPE_CONFIRMATION,
        ]);
        $token->save();
        $url = $token->getUrl();

        return $this->sendMessage([
            'email' => $user->email,
            'subject' => 'Kom godt i gang på KidUp',
            'type' => self::USER_WELCOME,
            'params' => [
                'profileName' => $user->profile->first_name,
            ],
            'urls' => [
                'verify' => $url,
                'profile' => Url::to('@web/user/settings/profile', true),
                'search' => Url::to('@web/search', true),
            ]
        ]);
    }

    /**
     * Welcome email after signup
     * @param $data
     * @return bool
     */
    public function recovery($data)
    {
        $user = $data;
        $token = new Token();
        $token->setAttributes([
            'user_id' => $user->id,
            'type' => Token::TYPE_RECOVERY,
        ]);
        $token->save();
        $url = $token->getUrl();

        return $this->sendMessage([
            'email' => $user->email,
            'subject' => \Yii::t('mail.recovery.subject', 'KidUp recovery request'),
            'type' => self::USER_RECOVERY,
            'params' => [
                'profileName' => $user->profile->first_name,
            ],
            'urls' => [
                'recovery' => $url,
            ]
        ]);
    }

    /**
     * Reconfirm Email
     * @param $user
     * @return bool
     */
    public function reconfirm($user)
    {
        Token::deleteAll(['user_id' => $user->id, 'type' => Token::TYPE_CONFIRMATION]);
        $token = new Token();
        $token->setAttributes([
            'user_id' => $user->id,
            'type' => Token::TYPE_CONFIRMATION,
        ]);
        $token->save();
        $url = $token->getUrl();

        return $this->sendMessage([
            'email' => $user->email,
            'subject' => 'Bekræft venligst din e-mail adresse - KidUp',
            'type' => self::USER_RECONFIRM,
            'params' => [
                'profileName' => $user->profile->first_name,
            ],
            'urls' => [
                'verify' => $url,
            ]
        ]);
    }
}