<?php
namespace mail\mails\review;

/**
 * Base class for the review emails
 */
class Request extends BaseReview
{
    /**
     * @var string where review can be made
     */
    public $reviewUrl;
}