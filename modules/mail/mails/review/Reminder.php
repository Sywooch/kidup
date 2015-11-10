<?php
namespace mail\mails\review;

use mail\mails\Mail;

/**
 * Base class for the review emails
 */
class Reminder extends BaseReview
{
    /**
     * @var string url where review can be made
     */
    public $reviewUrl;

    /**
     * @var int days left in which review can ve made
     */
    public $daysLeft;
}