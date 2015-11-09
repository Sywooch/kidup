<?php
namespace mail\mails;

use booking\models\Booking;
use Carbon\Carbon;
use mail\models\Mailer;
use Yii;
use yii\helpers\Url;

class BookingRenter extends Mailer
{
    /**
     * Renter booking request
     * @param \booking\models\Booking $booking
     * @return bool
     */
    public function request($booking)
    {
        $startDate = Carbon::createFromTimestamp($booking->time_from)->toFormattedDateString();
        $endDate = Carbon::createFromTimestamp($booking->time_to)->toFormattedDateString();

        return $this->sendMessage([
            'email' => $booking->renter->email,
            'subject' => "Bookinghenvendelse anmodning - {$booking->item->owner->profile->first_name} {$booking->item->owner->profile->last_name}",
            'type' => self::BOOKING_RENTER_REQUEST,
            'params' => [
                'itemName' => $booking->item->name,
                'profileName' => $booking->renter->profile->first_name,
                'ownerName' => $booking->item->owner->profile->first_name . ' ' . $booking->item->owner->profile->last_name,
                'payinAmount' => round($booking->amount_payin),
                'startDate' => $startDate,
                'endDate' => $endDate,
            ],
            'urls' => [
                'chat' => Url::to('@web/inbox/' . $booking->id . '/conversation', true),
                'booking' => Url::to('@web/booking/' . $booking->id, true),
            ]
        ]);
    }

    /**
     * Renter booking has been declined by owner
     * @param \booking\models\Booking $booking
     * @return bool
     */
    public function decline($booking)
    {
        $startDate = Carbon::createFromTimestamp($booking->time_from)->toFormattedDateString();
        $endDate = Carbon::createFromTimestamp($booking->time_to)->toFormattedDateString();

        return $this->sendMessage([
            'email' => $booking->renter->email,
            'subject' => "Reservation afvist – {$booking->item->name}",
            'type' => self::BOOKING_RENTER_DECLINE,
            'params' => [
                'itemName' => $booking->item->name,
                'profileName' => $booking->renter->profile->first_name,
                'startDate' => $startDate,
                'endDate' => $endDate,
            ],
            'urls' => [
                'website' => Url::to('@web/home', true),
            ]
        ]);
    }

    /**
     * Renter booking has been accepted by owner
     * @param \booking\models\Booking $booking
     * @return bool
     */
    public function confirmation($booking)
    {
        $startDate = Carbon::createFromTimestamp($booking->time_from)->toFormattedDateString();
        $endDate = Carbon::createFromTimestamp($booking->time_to)->toFormattedDateString();

        return $this->sendMessage([
            'email' => $booking->renter->email,
            'subject' => "Bookinghenvendelse for {$booking->item->name} for {$startDate} – {$endDate}",
            'type' => self::BOOKING_RENTER_CONFIRMATION,
            'params' => [
                'itemName' => $booking->item->name,
                'profileName' => $booking->renter->profile->first_name,
                'ownerName' => $booking->item->owner->profile->first_name . ' ' . $booking->item->owner->profile->last_name,
                'payinAmount' => round($booking->amount_payin),
                'startDate' => $startDate,
                'endDate' => $endDate,
            ],
            'urls' => [
                'chat' => Url::to('@web/inbox/' . $booking->conversation->id, true),
                'booking' => Url::to('@web/booking/' . $booking->id, true),
            ]
        ]);
    }

    /**
     * Renter booking receipt
     * @param \booking\models\Booking $booking
     * @return bool
     */
    public function receipt($booking)
    {
        $startDate = Carbon::createFromTimestamp($booking->time_from)->toFormattedDateString();
        $endDate = Carbon::createFromTimestamp($booking->time_to)->toFormattedDateString();

        $numberOfDays = Carbon::createFromTimestamp($booking->time_from)->diffInDays(Carbon::createFromTimestamp($booking->time_to));

        $address = $booking->item->location->street_name . ' ' . $booking->item->location->street_number . ',' . PHP_EOL .
            $booking->item->location->zip_code . ' ' . $booking->item->location->city . PHP_EOL . ', ' . PHP_EOL .
            $booking->item->location->country0->name;

        return $this->sendMessage([
            'email' => $booking->renter->email,
            'subject' => "Booking bekræftelse – {$booking->item->owner->profile->first_name} {$booking->item->owner->profile->last_name}",
            'type' => self::BOOKING_RENTER_RECEIPT,
            'params' => [
                'bookingId' => $booking->id,
                'profileName' => $booking->renter->profile->first_name . ' ' . $booking->item->owner->profile->last_name,
                'bookingLocation' => $address,
                'itemName' => $booking->item->name,
                'rentingDays' => $numberOfDays,
                'payinDate' => Carbon::createFromTimestamp($booking->payin->updated_at)->toFormattedDateString(),
                'amountPerDay' => $booking->amount_item / $numberOfDays . ' DKK',
                'amountItem' => $booking->amount_item . ' DKK',
                'amountServiceFee' => $booking->amount_payin - $booking->amount_item . ' DKK',
                'amountTotal' => $booking->amount_payin . ' DKK',
                'amountBalance' => '0 DKK',
                'startDate' => $startDate,
                'endDate' => $endDate,
                'nowDate' => Carbon::now()->toFormattedDateString(),
            ],
            'urls' => [
                'viewReceipt' => Url::to('@web/booking/' . $booking->id . '/receipt', true),
            ]
        ]);
    }

    /**
     * Renter booking is about to start
     * @param \booking\models\Booking $booking
     * @return bool
     */
    public function start($booking)
    {
        $startDate = Carbon::createFromTimestamp($booking->time_from)->toFormattedDateString();
        $endDate = Carbon::createFromTimestamp($booking->time_to)->toFormattedDateString();

        $address = $booking->item->location->street_name . ' ' . $booking->item->location->street_number . ', ' .
            $booking->item->location->zip_code . ', ' . $booking->item->location->city;

        return $this->sendMessage([
            'email' => $booking->renter->email,
            'subject' => "Reservationspåmindelse – {$startDate}",
            'type' => self::BOOKING_RENTER_STARTS,
            'params' => [
                'itemName' => $booking->item->name,
                'profileName' => $booking->renter->profile->first_name,
                'ownerName' => $booking->item->owner->profile->first_name . ' ' . $booking->item->owner->profile->last_name,
                'ownerLocation' => $address,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'phoneNumber' => $booking->item->owner->profile->phone_country . ' ' . $booking->item->owner->profile->phone_number
            ],
            'urls' => [
                'booking' => Url::to('@web/booking/' . $booking->id, true),
                'chat' => Url::to('@web/inbox/' . $booking->conversation->id, true),
                'help' => Url::to('@web/contact', true),
            ]
        ]);
    }

    /**
     * Payin of the booking has failed
     * @param Booking $booking
     * @return bool
     */
    public function failed($booking)
    {
        return $this->sendMessage([
            'email' => $booking->item->owner->email,
            'subject' => "Betaling mislykket",
            'type' => self::BOOKING_RENTER_PAYMENT_FAILED,
            'params' => [
                'itemName' => $booking->item->name,
                'profileName' => $booking->renter->profile->first_name,
                'kidUpEmail' => 'mailto:info@kidup.dk'
            ],
            'urls' => [
                'help' => Url::to('@web/contact', true),
                'booking' => Url::to('@web/booking/' . $booking->id, true),
            ]
        ]);
    }
}