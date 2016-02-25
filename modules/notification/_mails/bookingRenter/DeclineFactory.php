<?php
namespace mail\mails\bookingRenter;

use notifications\components\MailUserFactory;
use notifications\models\UrlFactory;
use yii\helpers\Url;

/**
 * Renter booking confirmation.
 */
class DeclineFactory
{

    public function create(\booking\models\Booking $booking)
    {
        $receiver = (new MailUserFactory())->createForUser($booking->renter);

        $e = new Decline();
        $e->setSubject('Booking declined');
        $e->setReceiver($receiver);
        $e->websiteUrl = UrlFactory::website();
        $e->itemName = $booking->item->name;
        $e->profileName = $booking->renter->profile->first_name;
        $e->startDate = $booking->time_from;
        $e->endDate = $booking->time_to;

        return $e;
    }

}