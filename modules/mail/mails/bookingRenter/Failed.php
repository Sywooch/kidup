<?php
namespace mail\mails\bookingRenter;

use mail\mails\Mail;


/**
 * Owner booking failed
 */
class Failed extends Mail {

    public $startDate;
    public $endDate;

    public $renterName;
    public $ownerName;
    public $profileName;
    public $phone;
    public $itemName;
    public $itemAmount;

    public $kidUpEmail;

    public $helpUrl;
    public $bookingUrl;

}