<?php
namespace mail\mails\bookingOwner;

use notifications\components\MailUserFactory;
use notifications\models\UrlFactory;
use yii\helpers\Url;

/**
 * Owner booking failed.
 */
class FailedFactory
{

    public function create(\booking\models\Booking $booking)
    {
        $receiver = (new MailUserFactory())->createForUser($booking->item->owner);

        $e = new Failed();
        $e->setSubject('Booking failed');
        $e->setReceiver($receiver);
        $e->itemName = $booking->item->name;
        $e->profileName = $booking->item->owner->profile->first_name;
        $e->renterName = $booking->renter->profile->first_name . ' ' . $booking->renter->profile->last_name;
        $e->itemAmount = round($booking->amount_item);
        $e->startDate = $booking->time_from;
        $e->endDate = $booking->time_to;
        $e->phone = $booking->renter->profile->phone_country . ' ' . $booking->renter->profile->phone_number;
        $e->kidUpEmail = 'info@kidup.dk';
        $e->helpUrl = UrlFactory::help();

        return $e;
    }

}