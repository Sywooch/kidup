<?php
namespace notification\components;

use Yii;

class MailTemplates
{

    public static $templates = [

        // Booking owner
        'booking_payout_owner' => [],

        // Booking renter
        'booking_receipt_renter' => [],
        'booking_request_renter' => [],

        // User
        'user_reconfirm' => [],
        'user_recovery' => [],
        'user_welcome' => [],
    ];

}