<?php
namespace mail\mails\bookingOwner;

use notifications\mails\Mail;

/**
 * Owner booking confirmation.
 */
class Confirmation extends Mail
{

    public $startDate;
    public $endDate;

    public $renterName;
    public $renterPhone;
    public $profileName;
    public $phone;
    public $itemName;
    public $payinAmount;
    public $payinId;
    public $numberOfDays;
    public $amountPerDay;
    public $amountItem;
    public $amountServiceFee;
    public $amountTotal;

    public $chatUrl;
    public $bookingUrl;
    public $helpUrl;
    public $contactUrl;

}