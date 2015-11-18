<?php
namespace mail\mails\user;

use \mail\models\Token;
use mail\models\TokenFactory;
use mail\models\UrlFactory;
use yii\helpers\Url;

/**
 * Recover email factory
 */
class WelcomeFactory
{

    /**
     * Create the Welcome Mail.
     *
     * @param \user\models\User $user User to send the e-mail to.
     * @return Mail E-mail.
     */
    public static function create($user)
    {
        $e = new Welcome();

        $token = TokenFactory::create($user, Token::TYPE_CONFIRMATION);
        $e->verifyUrl = $token->getUrl();
        $e->profileUrl = UrlFactory::profile($user);
        $e->searchUrl = UrlFactory::search();

        $e->subject = \Yii::t('mail.welcome.subject', 'Welcome to KidUp!');
        $e->emailAddress = $user->email;

        return $e;
    }

}