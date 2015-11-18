<?php
namespace mail\mails\bookingOwner;

use mail\mails\Mail;
/**
 * 'params' => [
 * 'itemName' => $booking->item->name,
 * 'profileName' => $booking->item->owner->profile->first_name,
 * 'renterName' => $booking->renter->profile->first_name . ' ' . $booking->renter->profile->last_name,
 * 'renterPhone' => $booking->renter->profile->phone_country . ' ' . $booking->renter->profile->phone_number,
 * 'payinAmount' => $booking->item->owner->profile->first_name . ' ' . $booking->item->owner->profile->last_name,
 * 'payinId' => $booking->payin->id,
 * 'startDate' => $startDate,
 * 'endDate' => $endDate,
 * 'numberOfDys' => $numberOfDays,
 * 'amountPerDay' => $booking->amount_item / $numberOfDays . ' DKK',
 * 'amountItem' => $booking->amount_item . ' DKK',
 * 'amountServiceFee' => $booking->amount_item - $booking->amount_payout . ' DKK',
 * 'amountTotal' => $booking->amount_payout . ' DKK',
 * ],
 * 'urls' => [
 * 'chat' => Url::to('@web/messages/' . $booking->conversation->id, true),
 * 'booking' => Url::to('@web/booking/' . $booking->id, true),
 * 'help' => Url::to('@web/help', true),
 * 'contact' => Url::to('@web/contact', true),
 * ]
 */

/**
 * Owner booking failed
 */
class Confirmation extends Mail {

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
    public $contentUrl;

}