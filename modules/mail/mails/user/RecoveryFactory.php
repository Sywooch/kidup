<?php
namespace mail\mails\user;

use \mail\models\Token;
/**
 * Recover email factory
 */
class RecoveryFactory
{
    public static function create(\user\models\User $user)
    {
        $e = new Recovery();

        $token = new \mail\models\Token();
        $token->setAttributes([
            'user_id' => $user->id,
            'type' => Token::TYPE_RECOVERY,
        ]);
        $token->save();
        $e->recoveryUrl = $token->getUrl();

        $e->subject = \Yii::t('mail.recovery.subject', 'KidUp recovery request');
        $e->emailAddress = $user->email;

        return $e;
    }
}