<?php
namespace notification\components;

use Yii;

class MailTemplates
{

    public static $templates = [

        // Booking owner
        'booking_payout_owner' => [
            'title' => 'Here is the payout and a receipt',
            'variables' => ['total_payout_amount', 'receipt']
        ],

        // User
        'user_reconfirm' => [
            'title' => 'Is this the real mail?',
            'variables' => []
        ],
        'user_recovery' => [
            'title' => 'I got your password',
            'variables' => []
        ],
        'user_welcome' => [
            'title' => 'Newest face on KidUp',
            'variables' => []
        ],
    ];

}