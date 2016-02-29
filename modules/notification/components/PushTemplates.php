<?php
namespace notification\components;

use Yii;

class PushTemplates
{

    public static $templates = [

        // Booking owner
        'booking_confirmed_owner' => [
            'title' => 'The rent is confirmed - celebrate!',
            'message' => 'Talk with {renter_username} about booking #{booking_id}',
            'fallback' => 'booking_confirmed_owner',
            'variables' => ['contact_renter_url', 'renter_username', 'renter_name', 'booking_id', 'renter_phone_url', 'renter_email_url']
        ],
        'booking_request_owner' => [
            'title' => 'Do you want to rent out? Reply now',
            'message' => '{renter_username} wants to rent "{item_name}" - answer now.',
            'fallback' => 'booking_request_owner',
            'variables' => ['renter_username', 'renter_name', 'item_name', 'total_payout_amount', 'time_before', 'accept_url', 'decline_url']
        ],
        'booking_start_owner' => [
            'title' => 'Remember your rent! It is starting soon',
            'message' => 'Remember you have a rent starting the {booking_date}. Contact {renter_username} here.',
            'fallback' => 'booking_start_owner',
            'variables' => ['renter_username', 'renter_name', 'booking_date', 'item_name', 'total_payout_amount', 'renter_phone_url', 'renter_email_url']
        ],

        // Booking renter
        'booking_confirmed_renter' => [
            'title' => 'The rent is confirmed - celebrate!',
            'message' => 'The rent has been accepted contact {owner_username} and plan the rest.',
            'fallback' => 'booking_confirmed_renter',
            'variables' => ['booking_date', 'booking_start_date', 'booking_end_date', 'owner_username', 'owner_name', 'item_name', 'receipt', 'contact_owner_url', 'owner_phone_url', 'owner_email_url']
        ],
        'booking_declined_renter' => [
            'title' => 'Your rent has been declined',
            'message' => 'The rent is unfortunately cancelled, what about renting something else?',
            'fallback' => 'booking_declined_renter',
            'variables' => ['owner_name', 'item_name', 'contact_owner_url', 'app_url']
        ],
        'booking_start_renter' => [
            'title' => 'Remember your rent! It is starting soon',
            'message' => 'Remember your rental starts the {booking_date}. Contact {owner_username} here.',
            'fallback' => 'booking_start_renter',
            'variables' => ['owner_username', 'owner_name', 'booking_date', 'owner_phone_url', 'owner_email_url', 'app_url']
        ],

        // Message
        'conversation_message_received' => [
            'message' => '{sender_username} wrote a message.',
            'fallback' => null,
            'variables' => ['sender_username']
        ],

        // Item
        'item_unfinished_reminder' => [
            'title' => 'Item unfinished',
            'message' => 'I saw you started to put up a product, want to finish it?',
            'fallback' => 'item_unfinished_reminder',
            'variables' => ['email_support', 'finish_product_url', 'faq_url']
        ],

        // Review
        'review_publish' => [
            'message' => 'You have {days_left} days left, to write your review.',
            'fallback' => null,
            'variables' => ['reviewer_username', 'days_left']
        ],
        'review_reminder' => [
            'title' => 'You forgot to star',
            'message' => 'You have {days_left} days left, to write your review.',
            'fallback' => 'review_reminder',
            'variables' => ['owner_name', 'review_url', 'days_left']
        ],
        'review_request' => [
            'title' => '3 or 5 stars, who knows?',
            'message' => 'Write a review regarding {owner_username} now, you both have 14 days.',
            'fallback' => 'review_request',
            'variables' => ['owner_username', 'review_url']
        ],

    ];

}