<?php
namespace mail\mails\user;

use notifications\mails\Mail;

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