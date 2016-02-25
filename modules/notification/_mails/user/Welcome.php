<?php
namespace mail\mails\user;

use notifications\mails\Mail;

/**
 * Welcome email
 */
class Welcome extends Mail
{
    /**
     * @var string url to the recovery
     */
    public $verifyUrl;
    /**
     * @var string profile url of user
     */
    public $profileUrl;
    /**
     * @var string Search url
     */
    public $searchUrl;
}