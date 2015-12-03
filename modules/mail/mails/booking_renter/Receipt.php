<?php
namespace mail\mails\bookingRenter;

use mail\mails\Mail;

/**
 * Renter booking receipt.
 */
class Receipt extends Mail {

    public $bookingId;
    public $profileName;
    public $itemName;
    public $rentingDays;
    public $payinDate;
    public $bookingLocation;
    public $amountPerDay;
    public $amountItem;
    public $amountServiceFee;
    public $amountTotal;
    public $amountBalance;
    public $startDate;
    public $endDate;
    public $receiptUrl;

}