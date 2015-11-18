<?php
namespace mail\mails\bookingRenter;

use mail\mails\Mail;

/**
 * Owner booking payout.
 */
class Payout extends Mail {

    public $startDate;
    public $endDate;

    public $profileName;
    public $ownerName;
    public $itemName;
    public $amountServiceFee;
    public $amountTotal;

    public $viewReceiptUrl;

}