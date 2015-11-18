<?php
namespace mail\mails\bookingOwner;

use mail\mails\MailUserFactory;
use mail\models\UrlFactory;
use yii\helpers\Url;

/**
 * Owner booking request.
 */
class StartFactory
{

    public function create(\booking\models\Booking $booking)
    {
        $receiver = (new MailUserFactory())->createForUser($booking->item->owner);

        $e = new Start();
        $e->setReceiver($receiver);
        $e->startDate = $booking->time_from;
        $e->endDate = $booking->time_to;
        $e->renterName = $booking->renter->profile->getFullName();
        $e->profileName = $booking->item->owner->profile->first_name;
        $e->itemName = $booking->item->name;
        $e->phone = $booking->renter->profile->phone_number;
        $e->itemAmount = round($booking->amount_item);
        $e->helpUrl = UrlFactory::help();

        return $e;
    }

}