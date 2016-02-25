<?php
namespace notification\components;

use Yii;

class PushTemplates
{

    public static $templates = [

        // Booking owner
        'booking_confirmed_owner' => [],
        'booking_request_owner' => [],
        'booking_start_owner' => [],

        // Booking renter
        'booking_confirmation_renter' => [],
        'booking_declined_renter' => [],
        'booking_start_renter' => [],

        // Message
        'conversation_message_received' => ['fallback' => 'fallback_conversation_message_received'],

        // Item
        'item_unfinished_reminder' => [],

        // Review
        'review_publish' => [],
        'review_reminder' => [],
        'review_request' => [],

    ];

}