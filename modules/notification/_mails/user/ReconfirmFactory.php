<?php
namespace mail\mails\user;

use \mail\models\Token;
use notifications\models\TokenFactory;

/**
 * Reconfirm email factory
 */
class ReconfirmFactory
{

    /**
     * Create the Reconfirm Mail.
     *
     * @param \user\models\User $user User to send the e-mail to.
     * @return Mail E-mail.
     */
    public function create($user)
    {
        $e = new Reconfirm();
        $e->setSubject('Reconfirm');
        $receiver = (new \mail\components\MailUserFactory())->create($user->profile->getFullName(), $user->email);
        $e->setReceiver($receiver);

        $token = TokenFactory::create($user, Token::TYPE_CONFIRMATION);

        $e->confirmUrl = $token->getUrl();
        $e->subject = \Yii::t('mail.reconfirm.subject', 'Confirm your email adress');
        $e->emailAddress = $user->email;

        return $e;
    }
}