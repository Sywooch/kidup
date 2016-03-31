<?php

namespace booking\models\invoice;

use booking\models\booking\Booking;
use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "Booking".
 */
class InvoiceFactory
{
    /**
     * @param Booking $booking
     * @return Invoice
     */
    public function createForBooking(Booking $booking)
    {
        $i = new Invoice();

        $ownerAddress = $booking->item->location->getFormattedAddress();

        try{
            $renterAddress = $booking->renter->locations[0]->getFormattedAddress();
        }catch (\Error $e){
            $renterAddress = 'unknown';
        }

        $i->setAttributes([
            'invoice_number' => (int)$this->getNewNumber(),
            'data' => Json::encode([
                'booking' => $booking,
                'item' => $booking->item,
                'owner' => $booking->item->owner,
                'ownerProfile' => $booking->item->owner->profile,
                'renter' => $booking->renter,
                'renterProfile' => $booking->renter->profile,
                'ownerAddress' => $ownerAddress,
                'renterAddress' => $renterAddress,
                'ownerCountry' => $booking->item->owner->locations[0]->country0,
                'renterCountry' => $booking->renter->locations[0]->country0
            ])
        ]);

        $i->save();
        return $i;
    }

    /**
     * Get a new invoice number
     * @return int|mixed
     */
    public function getNewNumber()
    {
        $i = Invoice::find()->orderBy('invoice_number DESC')->one();
        if ($i === null) {
            return 1;
        }
        return $i->invoice_number + 1;
    }
}
