<?php

namespace booking\models\payout;

use booking\models\booking\Booking;
use user\models\currency\Currency;
use Yii;

/**
 * This is the model class for table "payout".
 */
class PayoutFactory
{
    public function createFromBooking(Booking $booking)
    {
        $payout = new Payout();
        $payout->setAttributes([
            'status' => Payout::STATUS_WAITING_FOR_BOOKING_START,
            'amount' => $booking->amount_payout,
            'currency_id' => Currency::getUserOrDefault($booking->item->owner)->id,
            'user_id' => $booking->item->owner_id,
            'created_at' => time(),
        ]);
        $payout->save();
        $booking->payout_id = $payout->id;
        return $booking->save();
    }

}
