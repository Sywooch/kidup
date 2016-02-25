<?php
namespace mail\mails\bookingOwner;

use notifications\mails\Mail;


/**
 * Owner booking failed
 */
class Failed extends Mail
{

    public $startDate;
    public $endDate;

    public $renterName;
    public $profileName;
    public $phone;
    public $itemName;
    public $itemAmount;

    public $kidUpEmail;

    public $helpUrl;

}