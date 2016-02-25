<?php
namespace notification\components;

use Yii;

class PushTemplates
{

    public static $templates = [
        // Message
        'message_received' => [
            'fallback' => 'fallback_message_received'
        ],
    ];

}