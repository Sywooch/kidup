<?php
namespace mail\mails\bookingRenter;

use mail\components\MailUserFactory;
use mail\models\UrlFactory;
use yii\helpers\Url;

/**
 * Renter booking request.
 */
class RequestFactory
{

    public function create(\booking\models\Booking $booking)
    {
        $receiver = (new MailUserFactory())->createForUser($booking->renter);

        $e = new Request();
        $e->setSubject('Booking request');
        $e->setReceiver($receiver);
        $e->startDate = $booking->time_from;
        $e->endDate = $booking->time_to;
        $e->numberOfDays = $booking->getNumberOfDays();
        $e->renterName = $booking->renter->profile->getFullName();
        $e->ownerName = $booking->item->owner->profile->first_name;
        $e->profileName = $booking->renter->profile->first_name;
        $e->itemName = $booking->item->name;
        $e->payout = $booking->amount_payout . ' DKK';
        $e->dayPrice = $booking->getDayPrice();
        $e->responseUrl = Url::to('@web/booking/' . $booking->id . '/request', true);
        if (is_object($booking->conversation)) {
            $e->message = $booking->conversation->messages[0]->message;
            $e->chatUrl = UrlFactory::chat($booking->conversation);
        }

        return $e;
    }

}