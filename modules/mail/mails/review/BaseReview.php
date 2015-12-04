<?php
namespace mail\mails\review;

use mail\mails\Mail;

/**
 * Base class for the review emails
 */
class BaseReview extends Mail
{
    /**
     * @var string url to the recovery
     */
    public $otherName;
    public $profileName;
}