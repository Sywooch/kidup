<?php
namespace booking\models\booking;

use app\components\models\FactoryTrait;
use app\helpers\Event;
use booking\models\payin\PayinException;
use booking\models\payin\PayinFactory;
use Carbon\Carbon;
use user\models\currency\Currency;

/**
 * BookingFactory is responsible for the creation of bookings
 */
class BookingPaymentException extends BookingException
{
}

class BookingFactory extends Booking
{
    const EVENT_AFTER_BOOKING_CREATE = 'after-booking-create';

    use FactoryTrait;

    public $payment_nonce;


    public function rules()
    {
        return array_merge(parent::rules(), [
            ['payment_nonce', 'required']
        ]);
    }

    public function create(){

        $this->convertDates();
        $this->setScenario('init');
        $this->renter_id = \Yii::$app->user->id;
        $this->currency_id = Currency::getUserOrDefault()->id;
        $this->status = Booking::AWAITING_PAYMENT;
        $this->setPayinPrices();

        if(!$this->validate()){
            return $this;
        }
        if ($this->payin_id === null) {
            $payin = new PayinFactory();
            $payin = $payin->createFromBooking($this->payment_nonce, $this);

            $this->payin_id = $payin->id;
            $this->status = Booking::PENDING;
        }
        $this->save();
        Event::trigger($this, self::EVENT_AFTER_BOOKING_CREATE);
        return $this;
    }

    private function convertDates()
    {
        if (!is_numeric($this->time_from)) {
            $this->time_from = Carbon::createFromFormat('d-m-Y g:i:s', $this->time_from . ' 12:00:00')->timestamp;
        }

        if (!is_numeric($this->time_to)) {
            $this->time_to = Carbon::createFromFormat('d-m-Y g:i:s', $this->time_to . ' 12:00:00')->timestamp;
        }
    }

    public function setPayinPrices()
    {
        $prices = $this->item->getPriceForPeriod($this->time_from, $this->time_to, $this->currency);

        $this->amount_item = $prices['price'];
        $this->amount_payin = $prices['total'];
        $this->amount_payin_fee = round($prices['_detailed']['fee'], 4);
        $this->amount_payin_fee_tax = round($prices['_detailed']['feeTax'], 4);
        $this->amount_payin_costs = $this->amount_payin * 0.028 + 1.25;

        $payoutFee = \Yii::$app->params['payoutServiceFeePercentage'] * $prices['price'];
        $payoutFeeTax = $payoutFee * 0.25; // static tax for now
        $this->amount_payout = round($this->amount_item - $payoutFee - $payoutFeeTax);
        $this->amount_payout_fee = round($payoutFee, 4);
        $this->amount_payout_fee_tax = round($payoutFeeTax, 4);

        return $this;
    }
}