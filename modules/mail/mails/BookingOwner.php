<?php
namespace app\modules\mail\mails;

use app\modules\mail\models\Mailer;
use Carbon\Carbon;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use app\modules\booking\models\Booking;
class BookingOwner extends Mailer
{
    /**
     * Owner booking request
     * @param \app\modules\booking\models\Booking $data
     * @return bool
     */
    public function request($booking)
    {
        $startDate = Carbon::createFromTimestamp($booking->time_from)->toFormattedDateString();
        $endDate = Carbon::createFromTimestamp($booking->time_to)->toFormattedDateString();

        $numberOfDays = Carbon::createFromTimestamp($booking->time_from)->diffInDays(Carbon::createFromTimestamp($booking->time_to));
        return $this->sendMessage([
            'email' => $booking->item->owner->email,
            'subject' => "Bookinghenvendelse for {$booking->item->name} for {$startDate} – {$endDate}",
            'type' => self::BOOKING_OWNER_REQUEST,
            'params' => [
                'booking' => $booking,
                'itemName' => $booking->item->name,
                'profileName' => $booking->item->owner->profile->first_name,
                'renterName' => $booking->renter->profile->first_name . ' ' . $booking->renter->profile->last_name,
                'message' => $booking->conversation->messages[0]->message, // there should only be one message
                'payout' => round($booking->amount_payout) . ' DKK',
                'dayPrice' => ($booking->amount_item / $numberOfDays) . ' DKK',
                'startDate' => $startDate,
                'endDate' => $endDate,
            ],
            'urls' => [
                'response' => Url::to('@web/booking/' . $booking->id . '/request', true),
                'chat' => Url::to('@web/messages/' . $booking->conversation->id, true),
            ]
        ]);
    }

    /**
     * Owner booking has been accepted by owner
     * @param \app\modules\booking\models\Booking $booking
     * @return bool
     */
    public function confirmation($booking)
    {
        $startDate = Carbon::createFromTimestamp($booking->time_from)->toFormattedDateString();
        $endDate = Carbon::createFromTimestamp($booking->time_to)->toFormattedDateString();

        $numberOfDays = Carbon::createFromTimestamp($booking->time_from)->diffInDays(Carbon::createFromTimestamp($booking->time_to));

        return $this->sendMessage([
            'email' => $booking->item->owner->email,
            'subject' => "Reservation bekræftet - {$booking->renter->profile->first_name} {$booking->renter->profile->last_name}",
            'type' => self::BOOKING_OWNER_CONFIRMATION,
            'params' => [
                'itemName' => $booking->item->name,
                'profileName' => $booking->item->owner->profile->first_name,
                'renterName' => $booking->renter->profile->first_name . ' ' . $booking->renter->profile->last_name,
                'renterPhone' => $booking->renter->profile->phone_country . ' ' . $booking->renter->profile->phone_number,
                'payinAmount' => round($booking->amount_payin),
                'payinId' => $booking->payin->id,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'numberOfDys' => $numberOfDays,
                'amountPerDay' => round($booking->amount_item / $numberOfDays) . ' DKK',
                'amountItem' => round($booking->amount_item) . ' DKK',
                'amountServiceFee' => round($booking->amount_item - $booking->amount_payout) . ' DKK',
                'amountTotal' => round($booking->amount_payout) . ' DKK',
            ],
            'urls' => [
                'chat' => Url::to('@web/messages/' . $booking->conversation->id, true),
                'booking' => Url::to('@web/booking/' . $booking->id, true),
                'help' => Url::to('@web/help', true),
                'contact' => Url::to('@web/contact', true),
            ]
        ]);
    }

    /**
     * Owner booking has been cancelled by renter
     * @param \app\modules\booking\models\Booking $booking
     * @return bool
     */
    public function cancel($booking)
    {
        $startDate = Carbon::createFromTimestamp($booking->time_from)->toFormattedDateString();
        $endDate = Carbon::createFromTimestamp($booking->time_to)->toFormattedDateString();

        return $this->sendMessage([
            'email' => $booking->item->owner->email,
            'subject' => "Bookinghenvendelse for {$booking->item->name} for {$startDate} – {$endDate}",
            'type' => self::BOOKING_OWNER_CANCELLED,
            'params' => [
                'itemName' => $booking->item->name,
                'profileName' => $booking->item->owner->profile->first_name,
                'renterName' => $booking->renter->profile->first_name . ' ' . $booking->renter->profile->last_name,
                'payinAmount' => round($booking->amount_payin),
                'startDate' => $startDate,
                'endDate' => $endDate,
            ],
            'urls' => [
                'chat' => Url::to('@web/messages/' . $booking->conversation->id, true),
                'booking' => Url::to('@web/booking/' . $booking->id, true),
            ]
        ]);
    }

    /**
     * Owner booking receipt
     * @param \app\modules\booking\models\Booking $booking
     * @return bool
     */
    public function payout($booking)
    {
        $startDate = Carbon::createFromTimestamp($booking->time_from)->toFormattedDateString();
        $endDate = Carbon::createFromTimestamp($booking->time_to)->toFormattedDateString();

        return $this->sendMessage([
            'email' => $booking->item->owner->email,
            'subject' => "Udbetaling på {$booking->amount_payout} DKK sendt",
            'type' => self::BOOKING_OWNER_PAYOUT,
            'params' => [
                'profileName' => $booking->item->owner->profile->first_name,
                'itemName' => $booking->item->name,
                'amountServiceFee' => round($booking->amount_item - $booking->amount_payout) . ' DKK',
                'amountTotal' => round($booking->amount_payout) . ' DKK',
                'startDate' => $startDate,
                'endDate' => $endDate,
            ],
            'urls' => [
                'viewReceipt' => Url::to('@web/booking/' . $booking->id . '/receipt', true),
            ]
        ]);
    }

    /**
     * Owner booking is about to start
     * @param \app\modules\booking\models\Booking $booking
     * @return bool
     */
    public function start($booking)
    {
        $startDate = Carbon::createFromTimestamp($booking->time_from)->toFormattedDateString();
        $endDate = Carbon::createFromTimestamp($booking->time_to)->toFormattedDateString();

        return $this->sendMessage([
            'email' => $booking->item->owner->email,
            'subject' => "Reservations påmindelse – {$startDate}",
            'type' => self::BOOKING_OWNER_STARTS,
            'params' => [
                'itemName' => $booking->item->name,
                'profileName' => $booking->item->owner->profile->first_name,
                'renterName' => $booking->renter->profile->first_name . ' ' . $booking->renter->profile->last_name,
                'itemAmount' => round($booking->amount_item),
                'startDate' => $startDate,
                'endDate' => $endDate,
                'phoneNumber' => $booking->renter->profile->phone_country . ' ' . $booking->renter->profile->phone_number
            ],
            'urls' => [
                'booking' => Url::to('@web/booking/' . $booking->id, true),
                'chat' => Url::to('@web/messages/' . $booking->conversation->id, true),
                'help' => Url::to('@web/contact', true),
            ]
        ]);
    }

    /**
     * Payin of the booking has failed
     * @param Booking $booking
     */
    public function failed($booking){

        $startDate = Carbon::createFromTimestamp($booking->time_from)->toFormattedDateString();
        $endDate = Carbon::createFromTimestamp($booking->time_to)->toFormattedDateString();

        return $this->sendMessage([
            'email' => $booking->item->owner->email,
            'subject' => "Betaling mislykket",
            'type' => self::BOOKING_OWNER_PAYMENT_FAILED,
            'params' => [
                'itemName' => $booking->item->name,
                'profileName' => $booking->item->owner->profile->first_name,
                'renterName' => $booking->renter->profile->first_name . ' ' . $booking->renter->profile->last_name,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'kidUpEmail' => 'mailto:info@kidup.dk'
            ],
            'urls' => [
                'help' => Url::to('@web/contact', true),
            ]
        ]);
    }
}