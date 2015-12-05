<?php
namespace mail\mails\review;

/**
 * Base class for the review emails
 */
class Request extends BaseReview
{
    // there URL where the review can be made
    public $reviewUrl = '';
}