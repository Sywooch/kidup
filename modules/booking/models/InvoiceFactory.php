<?php

namespace booking\models;

use Carbon\Carbon;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
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

        $l = $booking->item->location;
        $loc = [];
        $loc[] = $l->street_name . ' ' . $l->street_number;
        $loc[] = $l->zip_code;
        $loc[] = $l->country0->name;
        $ownerAddress = implode(',', $loc);

        $l = $booking->renter->locations[0];
        $loc = [];
        $loc[] = $l->street_name . ' ' . $l->street_number;
        $loc[] = $l->zip_code;
        $loc[] = $l->country0->name;
        $renterAddress = implode(',', $loc);

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

    public function getNewNumber()
    {
        $i = Invoice::find()->orderBy('invoice_number DESC')->one();
        if ($i === null) {
            return 1;
        }
        return $i->invoice_number + 1;
    }
}
