<?php

$urls = [
//    '/' => 'home/home',
    '/' => 'home/home/fake-home',
    'home' => 'home/home',
    'change-language' => 'home/change-language',
    'super-secret-cache-flush' => 'home/super-secret-cache-flush',
    'search' => 'search/search',
    'search/<query>' => 'search/search',
    'login' => 'user/login',
    'logout' => 'user/security/logout',
    'user/logout' => 'user/security/logout',
    'user/<id:\d+>' => 'user/profile/show',
    'item/<id:\d+>' => 'item/view/index',
    'item/search' => 'item/search/index',
    'item/create' => 'item/create/index',
    'item/<id:\d+>/edit' => 'item/create/edit',
    'item/<id:\d+>/publish' => 'item/create/publish',
    'item/<id:\d+>/unpublish' => 'item/create/unpublish',
    'item/<id:\d+>/bookings' => 'item/list/bookings',
    'booking/<id:\d+>' => 'booking/view/index',
    'booking/confirm' => 'booking/default/confirm',
    'booking/current' => 'booking/list/current',
    'booking/previous' => 'booking/list/previous',
    'booking/by-item/<id:\d+>' => 'booking/list/by-item',
    'booking/<id:\d+>/receipt' => 'booking/view/receipt',
    'booking/<id:\d+>/invoice' => 'booking/view/invoice',
    'booking/<id:\d+>/request' => 'booking/default/request',
    'booking/<id:\d+>/conversation' => 'booking/default/conversation',
    'mail/show/<mail>' => 'notification/view/test',
    'mail/overview' => 'notification/view/overview',
    'mail/click' => 'notification/view/link',
    'mail/<id>' => 'notification/view/index',
    'mail/view/test' => 'notification/view/test',
    'sendgrid/webhook-apqcbec' => 'notification/sendgrid/webhook-apqcbec', // sendgrid incomming webhook
    'review/create/<bookingId:\d+>' => 'review/create/index',
    'inbox/<id:\d+>' => 'message/chat/conversation',
    'inbox' => 'message/chat/inbox',
    'images/<id>' => 'images/index',
    'images/<folder1>/<id>' => 'images/index',
    'images/<folder1>/<folder2>/<id>' => 'images/index',
    'images/<folder1>/<folder2>/<folder3>/<id>' => 'images/index',
    'conversation/<id:\d+>' => 'message.conversation',
    'p/<page>' => 'pages/default/wordpress',
    'p/<page>/<view>' => 'pages/default/<page>',
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['api/v1/items' => 'api/item'],
        'extraPatterns' => [
            'search' => 'search',
            'recommended' => 'recommended',
            'search-suggestions' => 'search-suggestions',
            'related' => 'related',
            'POST <id>/publish' => 'publish',
            'POST <id>/unpublish' => 'unpublish',
            '<id>/reviews' => 'reviews',
            'POST <id>/set-facet-value' => 'set-facet-value'
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['api/v1/bookings' => 'api/booking'],
        'extraPatterns' => [
            'costs' => 'costs',
            'GET payment-token' => 'payment-token',
            'GET as-owner' => 'as-owner',
            'GET accept' => 'accept',
            'GET decline' => 'decline',
            'GET <id>/reviews' => 'reviews',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['api/v1/conversations' => 'api/conversation'],
        'extraPatterns' => [
            '<id>/messages' => 'messages'
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['api/v1/locations' => 'api/location'],
        'extraPatterns' => [
            'get-by-ip' => 'get-by-ip',
            'get-by-lng-lat' => 'get-by-lng-lat'
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['api/v1/categories' => 'api/category'],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['api/v1/payout-methods' => 'api/payout-method'],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['api/v1/messages' => 'api/message'],
        'extraPatterns' => [
            'GET unread-count' => 'unread-count',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['api/v1/media' => 'api/media'],
        'extraPatterns' => [
            'image-sort' => 'image-sort'
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['api/v1/item-facets' => 'api/item-facet'],
        'extraPatterns' => [
            'available-for-item/<id>' => 'available-for-item',

        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['api/v1/users' => 'api/user'],
        'extraPatterns' => [
            'me' => 'me',
            '<id>/reviews' => 'reviews',
            'GET verify-phone-number' => 'verify-phone-number',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['api/v1/oauth2' => 'api/oauth2'],
        'extraPatterns' => [
            'token' => 'token',
            'refresh' => 'refresh',
            'facebook-login' => 'facebook-login'
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['api/v1/notifications' => 'api/notification'],
        'extraPatterns' => [
            'POST register' => 'register',
            'POST subscribe' => 'subscribe',
            'POST unsubscribe' => 'unsubscribe',
            'POST set-user' => 'set-user',
            'POST is-subscribed' => 'is-subscribed',
            'GET test' => 'test'
        ]
    ],
    'api/v1/pages/<page>' => 'api/pages/view',
    'api/v1/event' => 'api/event/index'
];

return $urls;