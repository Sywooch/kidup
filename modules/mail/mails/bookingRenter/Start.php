<?php
namespace mail\mails\bookingRenter;

use mail\mails\Mail;


/**
 * Owner booking request
 * @param \booking\models\Booking $booking
 * @return bool
 */
class Start extends Mail {

    public $startDate;
    public $endDate;

    public $profileName;
    public $renterName;
    public $ownerName;
    public $phone;
    public $itemName;
    public $itemAmount;

    public $helpUrl;

}