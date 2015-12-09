<?php
namespace mail\mails\bookingOwner;

use mail\components\MailUserFactory;
use mail\models\UrlFactory;
use yii\helpers\Url;

/**
 * Owner booking confirmation.
 */
class ConfirmationFactory
{

    public function create(\booking\models\Booking $booking)
    {
        $receiver = (new MailUserFactory())->createForUser($booking->item->owner);

        $e = new Confirmation();
        $e->setSubject('Booking confirmation');
        $e->setReceiver($receiver);
        $e->itemName = $booking->item->name;
        $e->profileName = $booking->item->owner->profile->first_name;
        $e->renterName = $booking->renter->profile->first_name . ' ' . $booking->renter->profile->last_name;
        $e->renterPhone = $booking->renter->profile->phone_country . ' ' . $booking->renter->profile->phone_number;
        if (is_object($booking->payin)) {
            $e->payinAmount = round($booking->payin->amount);
            $e->payinId = $booking->payin->id;
        }
        $e->startDate = $booking->time_from;
        $e->endDate = $booking->time_to;
        $e->numberOfDays = $booking->getNumberOfDays();
        $e->amountPerDay = ($booking->amount_item / $booking->getNumberOfDays()) . ' DKK';
        $e->amountItem = $booking->amount_item . ' DKK';
        $e->amountServiceFee = $booking->amount_item - $booking->amount_payout . ' DKK';
        $e->amountTotal = $booking->amount_payout . ' DKK';

        if (is_object($booking->conversation)) {
            $e->chatUrl = UrlFactory::chat($booking->conversation);
        }
        $e->bookingUrl = UrlFactory::booking($booking);
        $e->helpUrl = UrlFactory::help();
        $e->contactUrl = UrlFactory::contact();

        return $e;
    }

}