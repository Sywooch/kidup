<?php
namespace mail\mails\bookingRenter;

use mail\mails\Mail;

/**
 * Renter booking confirmation.
 */
class Decline extends Mail
{

    public $startDate;
    public $endDate;

    public $itemName;
    public $profileName;
    public $websiteUrl;

}