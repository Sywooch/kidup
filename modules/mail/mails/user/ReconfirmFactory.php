<?php
namespace mail\mails\user;

use \mail\models\Token;
use mail\models\TokenFactory;

/**
 * Recover email factory
 */
class ReconfirmFactory
{
    public function create(\user\models\User $user)
    {
        $e = new Reconfirm();

        $token = TokenFactory::create($user, Token::TYPE_CONFIRMATION);

        $e->confirmUrl = $token->getUrl();
        $e->subject = \Yii::t('mail.reconfirm.subject', 'Confirm your email adress');
        $e->emailAddress = $user->email;

        return $e;
    }
}