<?php
namespace mail\mails\bookingRenter;

use mail\components\MailUserFactory;
use mail\models\UrlFactory;
use yii\helpers\Url;

/**
 * Renter booking receipt.
 */
class ReceiptFactory
{

    public function create(\booking\models\Booking $booking)
    {
        $receiver = (new MailUserFactory())->createForUser($booking->renter);

        $e = new Receipt();
        $e->setReceiver($receiver);
        $e->startDate = $booking->time_from;
        $e->endDate = $booking->time_to;
        $e->bookingId = $booking->id;
        $e->profileName = $booking->renter->profile->first_name . ' ' . $booking->renter->profile->last_name;
        $e->bookingLocation = $booking->getLocation(true);
        $e->itemName = $booking->item->name;
        $e->rentingDays = $booking->getNumberOfDays();
        if (is_object($booking->payin)) {
            $e->payinDate = Carbon::createFromTimestamp($booking->payin->updated_at)->toFormattedDateString();
        }
        $e->amountPerDay = ($booking->amount_item / $booking->getNumberOfDays()) . ' DKK';
        $e->amountItem = $booking->amount_item . ' DKK';
        $e->amountServiceFee = ($booking->amount_payin - $booking->amount_item) . ' DKK';
        $e->amountTotal = $booking->amount_payin . ' DKK';
        $e->amountBalance = '0 DKK';
        $e->receiptUrl = UrlFactory::receipt($booking);

        return $e;
    }

}