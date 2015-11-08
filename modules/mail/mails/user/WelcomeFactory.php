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
    public static function create(\user\models\User $user)
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