<?php

namespace booking\models;

use Carbon\Carbon;
use item\models\Item;
use user\models\base\Currency;
use Yii;

/**
 * BookingFactory is responsible for the creation of bookings
 */
class BookingDatesOverlapException extends BookingException
{
}

class BookingInvalideDatesException extends BookingException
{
}

class BookingPaymentException extends BookingException
{
}

/**
 * @property Item $item
 * @property Booking $booking
 */
class BookingFactory
{
    private $booking;
    /**
     * @var Payin
     */
    private $payin;
    private $item;

    public function create($from, $to, Item $item, $paymentNonce, $message)
    {
        $this->item = $item;
        $this->booking = new Booking();

        if (!$this->setDatesAndValidate($from, $to)) {
            return false;
        }

        $this->booking->setScenario('init');
        $this->booking->item_id = $item->id;
        $this->booking->renter_id = \Yii::$app->user->id;
        $this->booking->currency_id = Currency::getUserOrDefault()->id;
        $this->booking->status = Booking::AWAITING_PAYMENT;
        $this->booking->setPayinPrices();



        $this->createPayin($paymentNonce);

        try {
            $this->payin->authorize();
        } catch (PayinException $e) {
            throw new BookingPaymentException("Payment failed", null, $e->getPrevious());
        }



        $this->booking->payin_id = $this->payin->id;
        $this->booking->status = Booking::PENDING;
        $this->booking->setExpireDate();
        $this->booking->save();
        $this->payin->status = Payin::STATUS_AUTHORIZED;
        $this->payin->save();

        $this->booking->startConversation($message);
        return $this->booking;
    }

    private function createPayin($paymentNonce)
    {
        $this->payin = new Payin();
        $this->payin->nonce = $paymentNonce;
        $this->payin->status = Payin::STATUS_INIT;
        $this->payin->currency_id = Currency::getUserOrDefault()->id;
        $this->payin->user_id = \Yii::$app->user->id;
        $this->payin->amount = $this->booking->amount_payin;
        return $this->payin->save();
    }

    private function setDatesAndValidate($from, $to)
    {
        if(is_numeric($from)){
            $this->booking->time_from = $from;
        }else{
            $this->booking->time_from = Carbon::createFromFormat('d-m-Y g:i:s', $from . ' 12:00:00')->timestamp;
        }

        if(is_numeric($to)){
            $this->booking->time_to = $to;
        }else{
            $this->booking->time_to = Carbon::createFromFormat('d-m-Y g:i:s', $to . ' 12:00:00')->timestamp;
        }
        if ($this->booking->time_to <= $this->booking->time_from) {
            throw new BookingInvalideDatesException("To date should be larger then from date");
        }
        // see if it clashes with another booking
        // https://stackoverflow.com/questions/325933/determine-whether-two-date-ranges-overlap
        $overlapping = Booking::find()->where(':from < time_to and :to > time_from and item_id = :item_id and status = :status',
            [
                ':from' => $this->booking->time_from,
                ':to' => $this->booking->time_to,
                ':item_id' => $this->item->id,
                ':status' => Booking::ACCEPTED
            ])->count();
        if ($overlapping > 0) {
            throw new BookingDatesOverlapException();
        }
        return true;
    }
}
