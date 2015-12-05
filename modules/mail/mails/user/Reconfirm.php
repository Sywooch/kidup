<?php
namespace mail\mails\user;

use mail\mails\Mail;

/**
 * Recovery email
 */
class Reconfirm extends Mail
{
    /**
     * @var string url to the recovery
     */
    public $confirmUrl;
}