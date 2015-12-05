<?php
namespace mail\mails\user;

use mail\mails\Mail;

/**
 * Recovery email
 */
class Recovery extends Mail
{
    /**
     * @var string url to the recovery
     */
    public $recoveryUrl;
}