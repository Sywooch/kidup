<?php
namespace mail\mails;

use user\models\User;

/**
 * A mail user factory.
 *
 * Class MailUserFactory
 * @package mail\mails
 */
class MailUserFactory
{

    /**
     * Create a mail user based on a real user.
     *
     * @param \user\models\User $user User to create the mail user for.
     * @return MailUser The mail user.
     */
    public function createForUser(User $user)
    {
        $mailUser = new MailUser();
        $mailUser->name = $user->profile->getFullName();
        $mailUser->email = $user->email;
        return $mailUser;
    }

    /**
     * Create a mail user based on attributes.
     *
     * @param $name   string  Name the mail user has.
     * @param $email  string  E-mail address of the mail user.
     * @return MailUser The mail user.
     */
    public function create($name, $email)
    {
        $mailUser = new MailUser();
        $mailUser->name = $name;
        $mailUser->email = $email;
        return $mailUser;
    }

}