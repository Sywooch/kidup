<?php
namespace mail\mails\item;

use notifications\mails\Mail;

/**
 * Base class for the review emails
 */
class UnfinishedReminder extends Mail
{
    /**
     * @var string Url of own profile
     */
    public $profileUrl;

    /**
     * @var string name of the unpublished items
     */
    public $itemName;

    /**
     * @var string url to the page where item can be finished
     */
    public $itemUrl;
}