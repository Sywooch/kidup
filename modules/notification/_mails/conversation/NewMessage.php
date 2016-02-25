<?php
namespace mail\mails\conversation;

use notifications\mails\Mail;

/**
 * New message confirmation.
 */
class NewMessage extends Mail
{


    public $message;
    public $profileName;
    public $senderName;
    public $title;

    public $chatUrl;

}