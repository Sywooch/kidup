<?php
namespace mail\mails\bookingRenter;

use mail\mails\Mail;

/**
 * Renter booking request.
 */
class Request extends Mail {

    public $startDate;
    public $endDate;

    public $renterName;
    public $profileName;
    public $ownerName;
    public $itemName;
    public $dayPrice;
    public $payout;
    public $responseUrl;

    public $message;
    public $chatUrl;

    public $numberOfDays;

}