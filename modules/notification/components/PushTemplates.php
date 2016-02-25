<?php
namespace notification\components;

use Yii;

class PushTemplates
{

    public static $templates = [
        'test_notification' => [
            'variables' => ['user'],
            'fallback' => 'fallback_test_notification'
        ],
        'test_notification2' => [
            'variables' => ['user'],
            'fallback' => null
        ]
    ];

}