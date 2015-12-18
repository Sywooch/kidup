<?php
namespace mail\mails\bookingOwner;

use mail\components\MailUserFactory;
use mail\models\UrlFactory;
use yii\helpers\Url;

/**
 * Owner booking payout.
 */
class PayoutFactory
{

    public function create(\booking\models\Booking $booking)
    {
        $receiver = (new MailUserFactory())->createForUser($booking->item->owner);

        $e = new Payout();
        $e->setSubject('Booking payout');
        $e->setReceiver($receiver);
        $e->profileName = $booking->item->owner->profile->first_name;
        $e->itemName = $booking->item->name;
        $e->amountServiceFee = $booking->amount_item - $booking->amount_payout . ' DKK';
        $e->amountTotal = $booking->amount_payout . ' DKK';
        $e->startDate = $booking->time_from;
        $e->endDate = $booking->time_to;
        $e->viewReceiptUrl = UrlFactory::receipt($booking);

        return $e;
    }

}