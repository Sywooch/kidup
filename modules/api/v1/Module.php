<?php

namespace api\v1;

class Module extends \yii\base\Module
{
    public $controllerNamespace = '\api\v1\controllers';

    /** @var array The rules to be used in URL management. */
    public $urlRules = [
        [
            'class' => 'yii\rest\UrlRule',
            'controller' => ['items' => 'item'],
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
            'controller' => ['bookings' => 'booking'],
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
            'controller' => ['conversations' => 'conversation'],
            'extraPatterns' => [
                '<id>/messages' => 'messages',
                'GET unread-count' => 'unread-count',
            ]
        ],
        [
            'class' => 'yii\rest\UrlRule',
            'controller' => ['locations' => 'location'],
            'extraPatterns' => [
                'get-by-ip' => 'get-by-ip',
                'get-by-lng-lat' => 'get-by-lng-lat'
            ]
        ],
        [
            'class' => 'yii\rest\UrlRule',
            'controller' => ['categories' => 'category'],
            'extraPatterns' => [
                'GET unread-count' => 'unread-count',
            ]
        ],
        [
            'class' => 'yii\rest\UrlRule',
            'controller' => ['payout-methods' => 'payout-method'],
        ],
        [
            'class' => 'yii\rest\UrlRule',
            'controller' => ['messages' => 'message'],
        ],
        [
            'class' => 'yii\rest\UrlRule',
            'controller' => ['wish-list-items' => 'wish-list-item'],
        ],
        [
            'class' => 'yii\rest\UrlRule',
            'controller' => ['media' => 'media'],
            'extraPatterns' => [
                'image-sort' => 'image-sort'
            ]
        ],
        [
            'class' => 'yii\rest\UrlRule',
            'controller' => ['item-facets' => 'item-facet'],
            'extraPatterns' => [
                'available-for-item/<id>' => 'available-for-item',

            ]
        ],
        [
            'class' => 'yii\rest\UrlRule',
            'controller' => ['kode-ups' => 'kode-up'],
            'extraPatterns' => [
                'recommendations' => 'recommendations',

            ]
        ],
        [
            'class' => 'yii\rest\UrlRule',
            'controller' => ['users' => 'user'],
            'extraPatterns' => [
                'me' => 'me',
                'GET recover' => 'recover',
                '<id>/reviews' => 'reviews',
                'GET verify-phone-number' => 'verify-phone-number',
            ]
        ],
        [
            'class' => 'yii\rest\UrlRule',
            'controller' => ['oauth2' => 'oauth2'],
            'extraPatterns' => [
                'token' => 'token',
                'refresh' => 'refresh',
                'facebook-login' => 'facebook-login'
            ]
        ],
        [
            'class' => 'yii\rest\UrlRule',
            'controller' => ['notifications' => 'notification'],
            'extraPatterns' => [
                'POST register' => 'register',
                'POST subscribe' => 'subscribe',
                'POST unsubscribe' => 'unsubscribe',
                'POST set-user' => 'set-user',
                'POST is-subscribed' => 'is-subscribed',
                'GET test' => 'test',
                'GET mail-view' => 'mail-view'
            ]
        ],
        'pages/<page>' => 'pages/view',
        'event' => 'event/index',
        'event/error' => 'event/error'
    ];
}
