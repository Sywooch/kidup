<?php
namespace notification\components;

use Yii;

class PushTemplates
{

    public static $templates = [

        // Booking owner
        'booking_confirmed_owner' => [
            'fallback' => 'booking_confirmed_owner',
            'variables' => ['contact_renter_url', 'renter_name', 'booking_id', 'renter_phone_url', 'renter_email_url']
        ],
        'booking_request_owner' => [
            'fallback' => 'booking_request_owner',
            'variables' => [
                'renter_name',
                'item_name',
                'total_payout_amount',
                'time_before',
                'accept_url',
                'decline_url'
            ]
        ],
        'booking_start_owner' => [
            'fallback' => 'booking_start_owner',
            'variables' => [
                'renter_name',
                'booking_start_date',
                'item_name',
                'total_payout_amount',
                'renter_phone_url',
                'renter_email_url'
            ]
        ],

        // Booking renter
        'booking_confirmed_renter' => [
            'fallback' => 'booking_confirmed_renter',
            'variables' => [
                'booking_start_date',
                'booking_end_date',
                'owner_name',
                'item_name',
                'contact_owner_url',
                'owner_phone_url',
                'owner_email_url'
            ]
        ],
        'booking_declined_renter' => [
            'fallback' => 'booking_declined_renter',
            'variables' => ['owner_name', 'item_name', 'contact_owner_url', 'app_url']
        ],
        'booking_start_renter' => [
            'fallback' => 'booking_start_renter',
            'variables' => ['owner_name', 'booking_start_date', 'owner_phone_url', 'owner_email_url', 'app_url']
        ],

        // Message
        'conversation_message_received' => [
            'fallback' => null,
            'variables' => ['sender_name']
        ],

        // Review
        'review_publish' => [
            'fallback' => null,
            'variables' => ['reviewer', 'days_left']
        ],
        'review_reminder' => [
            'fallback' => 'review_reminder',
            'variables' => ['reviewed_user', 'review_url', 'days_left']
        ],
        'review_request' => [
            'fallback' => 'review_request',
            'variables' => ['reviewed_user', 'review_url']
        ],

    ];

}