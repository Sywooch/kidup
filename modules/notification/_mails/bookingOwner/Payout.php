<?php
namespace mail\mails\bookingOwner;

use notifications\mails\Mail;

/**
 * Owner booking payout.
 */
class Payout extends Mail
{

    public $startDate;
    public $endDate;

    public $profileName;
    public $itemName;
    public $amountServiceFee;
    public $amountTotal;

    public $viewReceiptUrl;

}