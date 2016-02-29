<?php
namespace notification\components;

use Yii;

class MailTemplates
{

    public static $templates = [

        // Booking owner
        'booking_payout_owner' => [
            'title' => 'Here is the payout and a receipt',
            'variables' => ['total_payout_amount', 'receipt', 'booking_start_date', 'booking_end_date', 'email_support']
        ],

        // User
        'user_reconfirm' => [
            'title' => 'Is this the real mail?',
            'variables' => ['confirm_url']
        ],
        'user_recovery' => [
            'title' => 'I got your password',
            'variables' => ['recovery_url']
        ],
        'user_welcome' => [
            'title' => 'Newest face on KidUp',
            'variables' => ['rent_url', 'rent_out_url']
        ],
    ];

}