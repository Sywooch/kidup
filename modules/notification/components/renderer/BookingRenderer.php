<?php
namespace notification\components\renderer;

use booking\models\Booking;
use notification\components\Renderer;

class BookingRenderer
{

    /**
     * Load all booking render variables.
     *
     * @param Booking $booking The booking.
     * @return array All the render variables.
     */
    public function loadBooking(Booking $booking) {
        $result = [];

        // Renter
        $result['renter_name'] = $booking->renter->profile->getName();
        $result['renter_email_url'] = 'mailto:' . $booking->renter->email;
        if (strlen($booking->renter->profile->phone_number) > 0) {
            $result['renter_phone_url'] = 'tel:' . $booking->renter->profile->phone_number;
        }

        // Owner
        $result['owner_name'] = $booking->item->owner->profile->getName();
        $result['owner_email_url'] = 'mailto:' . $booking->renter->email;
        if (strlen($booking->renter->profile->phone_number) > 0) {
            $result['owner_phone_url'] = 'tel:' . $booking->renter->profile->phone_number;
        }

        // Booking
        $result['booking_start_date'] = Renderer::displayDateTime($booking->time_from);
        $result['booking_end_date']= Renderer::displayDateTime($booking->time_to);

        return $result;
    }

}