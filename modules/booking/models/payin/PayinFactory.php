<?php
namespace booking\models\payin;

use app\components\models\FactoryTrait;
use booking\models\booking\Booking;
use user\models\currency\Currency;

class PayinFactory extends Payin
{
    use FactoryTrait;

    public function createFromBooking($paymentNonce, Booking $booking)
    {
        $this->nonce = $paymentNonce;
        $this->status = Payin::STATUS_INIT;
        $this->currency_id = Currency::getUserOrDefault($booking->renter)->id;
        $this->user_id = $booking->renter_id;
        $this->amount = $booking->amount_payin;
        $payin = $this->create();

        try {
            $payin->authorize();
        } catch (PayinException $e) {
            throw new PayinException($e->getMessage(), null, $e);
        }

        return $payin;
    }
}