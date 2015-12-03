<?php
namespace mail\mails\bookingRenter;

use mail\mails\Mail;


/**
 * Renter booking request.
 */
class Start extends Mail
{

    public $startDate;
    public $endDate;

    public $profileName;
    public $renterName;
    public $ownerName;
    public $ownerPhone;
    public $ownerLocation;
    public $itemName;
    public $itemAmount;

    public $helpUrl;

}