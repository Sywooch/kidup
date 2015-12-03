<?php
namespace mail\mails\bookingOwner;

use mail\mails\Mail;


/**
 * Owner booking request
 * @param \booking\models\Booking $booking
 * @return bool
 */
class Start extends Mail
{

    public $startDate;
    public $endDate;

    public $profileName;
    public $renterName;
    public $phone;
    public $itemName;
    public $itemAmount;

    public $helpUrl;

}