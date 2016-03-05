<?php
namespace notification\components;

use Yii;

class MailTemplates
{

    public static $templates = [

        // Booking owner
        'booking_payout_owner' => [
            'variables' => ['total_payout_amount', 'receipt', 'booking_start_date', 'booking_end_date', 'email_support']
        ],

        // User
        'user_reconfirm' => [
            'variables' => ['confirm_url']
        ],
        'user_recovery' => [
            'variables' => ['recovery_url']
        ],
        'user_welcome' => [
            'variables' => [
                'rent_url',
                'rent_out_url',
                'profile_url',
                'social_media_url',
                'email_support',
                'faq_url',
                'app_url'
            ]
        ],
    ];

}