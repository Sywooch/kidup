<?php
namespace mail\mails\user;

use \mail\models\Token;

/**
 * Recover email factory
 */
class RecoveryFactory
{

    /**
     * Create the Recovery Mail.
     *
     * @param \user\models\User $user User to send the e-mail to.
     * @return Mail Recovery.
     */
    public static function create($user)
    {
        $e = new Recovery();

        $token = new Token();
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