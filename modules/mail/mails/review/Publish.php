<?php
namespace mail\mails\review;

use mail\mails\Mail;

/**
 * Base class for the review emails
 */
class Publish extends BaseReview
{
    /**
     * @var string Url of own profile
     */
    public $profileUrl;
}