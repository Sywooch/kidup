<?php
$vendorDir = dirname(__DIR__) . '/vendor';
$params = require(__DIR__ . '/params.php');
$components = require(__DIR__ . '/components.php');
include_once (__DIR__ . '/keys/load_keys.php'); // sets the var keys

$config = [
    'id' => 'KidUp',
    'name' => 'KidUp',
    'basePath' => dirname(__DIR__),
    'sourceLanguage' => 'en', // this allows en-US translations to overwrite the default messages
    'bootstrap' => [
        'log',
        'user\\Bootstrap',
        'mail\\Bootstrap',
        'message\\Bootstrap',
        'item\\Bootstrap',
        'images\\Bootstrap',
        'booking\\Bootstrap',
        'home\\Bootstrap',
        'pages\\Bootstrap',
        'admin\\Bootstrap',
        'search\\Bootstrap',
        'api\\Bootstrap',
//        'docGenerator'
    ],
    'extensions' => array_merge(
        require($vendorDir . '/yiisoft/extensions.php')
    ),
    'modules' => [
        'user' => [
            'class' => '\user\Module',
            'enableUnconfirmedLogin' => true,
            'confirmWithin' => 365 * 24 * 60 * 60,
            'cost' => 13,
            'admins' => ['admin'],
            'enableConfirmation' => true,
            'enableFlashMessages' => false,
        ],
        'gridview' =>       ['class' => '\kartik\grid\Module'],
        'home' =>           ['class' => '\home\Module'],
        'item' =>           ['class' => '\item\Module'],
        'images' =>         ['class' => '\images\Module'],
        'message' =>        ['class' => '\message\Module'],
        'booking' =>        ['class' => '\booking\Module'],
        'mail' =>           ['class' => '\mail\Module'],
        'pages' =>          ['class' => '\pages\Module'],
        'review' =>         ['class' => '\review\Module'],
        'admin' =>          ['class' => '\admin\Module'],
        'search' =>         ['class' => '\search\Module'],
        'api' =>            ['class' => '\api\Module'],
        'social' => [
            // the module class
            'class' => 'kartik\social\Module',
            // the global settings for the facebook plugins widget
            'facebook' => [
                'appId' => '1515825585365803',
                'secret' => $keys['facebook_oauth_secret'],
            ],
            // the global settings for the google analytic plugin widget
            'googleAnalytics' => [
                'id' => 'UA-57682247-1',
                'domain' => 'kidup.dk',
            ],
        ],
    ],
    'components' => $components,
    'params' => $params,
    'aliases' => [
        '@pages' => '@app/modules/pages',
        '@user' => '@app/modules/user',
        '@mail' => '@app/modules/mail',
        '@home' => '@app/modules/home',
        '@item' => '@app/modules/item',
        '@message' => '@app/modules/message',
        '@images' => '@app/modules/images',
        '@booking' => '@app/modules/booking',
        '@review' => '@app/modules/review',
        '@admin' => '@app/modules/admin',
        '@search' => '@app/modules/search',
        '@api' => '@app/modules/api',
        '@tests' => '@app/tests/codeception',
    ],
];

if (YII_ENV == 'dev') {
    // configuration adjustments for 'dev' environment

    if(YII_DEBUG && !YII_CACHE){
        $config['bootstrap'][] = 'debug';
        $config['modules']['debug'] = [
            'class' => 'yii\debug\Module',
            'allowedIPs' => ['192.168.33.1'] // adjust this to your needs
        ];

        $config['bootstrap'][] = 'gii';
        $config['modules']['gii'] = [
            'class' => 'yii\gii\Module',
            'allowedIPs' => ['192.168.33.1'] // adjust this to your needs
        ];
        $config['modules']['gii']['generators'] = [
            'kartikgii-crud' => ['class' => 'warrence\kartikgii\crud\Generator'],
        ];
    }
}

return $config;