<?php
namespace mail\mails\bookingOwner\Request;

use mail\mails\Mail;


/**
 * Owner booking request
 * @param \booking\models\Booking $booking
 * @return bool
 */
class Request extends Mail{

    public $startDate;
    public $endDate;

    public $numberOfDays;

}