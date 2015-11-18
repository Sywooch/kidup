<?php
namespace mail\mails\bookingRenter;

use mail\mails\MailUserFactory;
use mail\models\UrlFactory;
use yii\helpers\Url;

/**
 * Renter booking failed.
 */
class FailedFactory
{

    public function create(\booking\models\Booking $booking)
    {
        $receiver = (new MailUserFactory())->createForUser($booking->renter);

        $e = new Failed();
        $e->setReceiver($receiver);
        $e->itemName = $booking->item->name;
        $e->profileName = $booking->renter->profile->first_name;
        $e->ownerName = $booking->item->owner->profile->first_name;
        $e->renterName = $booking->renter->profile->first_name . ' ' . $booking->renter->profile->last_name;
        $e->itemAmount = round($booking->amount_item);
        $e->startDate = $booking->time_from;
        $e->endDate = $booking->time_to;
        $e->phone = $booking->renter->profile->phone_country . ' ' . $booking->renter->profile->phone_number;
        $e->kidUpEmail = 'info@kidup.dk';
        $e->helpUrl = UrlFactory::help();
        $e->bookingUrl = UrlFactory::booking($booking);

        return $e;
    }

}