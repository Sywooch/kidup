<?php
namespace mail\mails\bookingOwner\Request;

use booking\models\base\Booking;
use Carbon\Carbon;


/**
 * Owner booking request
 * @param \booking\models\Booking $booking
 * @return bool
 */
class RequestFactory{

    public $startDate;
    public $endDate;

    public $template = '';

}