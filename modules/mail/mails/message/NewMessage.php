<?php
namespace mail\mails\message;

use mail\mails\Mail;

class NewMessage extends Mail{

    /**
     * @var string message to to receiver
     */
    public $message;

    /**
     * @var string url to the conversation
     */
    public $conversationUrl;

    /**
     * @var string name of the sending user
     */
    public $otherUserName;
}