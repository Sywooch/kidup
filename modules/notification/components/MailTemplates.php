<?php
namespace notification\components;

use Yii;

class MailTemplates
{

    public static $templates = [
        // Booking owner
        'booking_confirmed_owner' => [
            'variables' => ['user']
        ],
        'booking_failed_owner' => [],
        'booking_payout_owner' => [],
        'booking_request_owner' => [],
        'booking_start_owner' => [],

        // Booking renter
        'booking_confirmation_renter' => [],
        'booking_declined_renter' => [],
        'booking_failed_renter' => [],
        'booking_receipt_renter' => [],
        'booking_request_renter' => [],
        'booking_start_renter' => [],

        // Conversation
        'conversation_message_received' => [],

        // Item
        'item_unfinished_reminder' => [],

        // Review
        'review_publish' => [],
        'review_reminder' => [],
        'review_request' => [],

        // User
        'user_reconfirm' => [],
        'user_recovery' => [],
        'user_welcome' => [],

        // Example dummy
        'example' => [
            'variables' => ['user', 'bla'],
        ]
    ];

}