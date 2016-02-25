<?php
namespace notification\components;

use Yii;

class MailTemplates
{

    public static $templates = [
        'booking_owner_confirmation' => [
            'variables' => ['user'],
        ],
        'example' => [
            'variables' => ['user', 'bla'],
        ]
    ];

}