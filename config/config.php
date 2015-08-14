<?php
$vendorDir = dirname(__DIR__) . '/vendor';
$params = require(__DIR__ . '/params.php');
$components = require(__DIR__ . '/components.php');
$keyFile = __DIR__ . '/../config/keys/keys.env';
$keys = (new \josegonzalez\Dotenv\Loader($keyFile))->parse()->toArray();

$config = [
    'id' => 'KidUp',
    'name' => 'KidUp',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'app\\modules\\user\\Bootstrap',
        'app\\modules\\mail\\Bootstrap',
        'app\\modules\\message\\Bootstrap',
        'app\\modules\\item\\Bootstrap',
        'app\\modules\\images\\Bootstrap',
        'app\\modules\\booking\\Bootstrap',
        'app\\modules\\home\\Bootstrap',
        'app\\modules\\pages\\Bootstrap',
        'app\\modules\\admin\\Bootstrap',
        'app\\modules\\search\\Bootstrap',
    ],
    'extensions' => array_merge(
        require($vendorDir . '/yiisoft/extensions.php')
    ),
    'modules' => [
        'user' => [
            'class' => 'app\modules\user\Module',
            'enableUnconfirmedLogin' => true,
            'confirmWithin' => 365 * 24 * 60 * 60,
            'cost' => 13,
            'admins' => ['admin'],
            'enableConfirmation' => true,
            'enableFlashMessages' => false,
        ],
        'gridview' =>       ['class' => '\kartik\grid\Module'],
        'home' =>           ['class' => 'app\modules\home\Module'],
        'item' =>           ['class' => 'app\modules\item\Module'],
        'images' =>         ['class' => 'app\modules\images\Module'],
        'message' =>        ['class' => 'app\modules\message\Module'],
        'booking' =>        ['class' => 'app\modules\booking\Module'],
        'mail' =>           ['class' => 'app\modules\mail\Module'],
        'pages' =>          ['class' => 'app\modules\pages\Module'],
        'review' =>         ['class' => 'app\modules\review\Module'],
        'admin' =>          ['class' => 'app\modules\admin\Module'],
        'search' =>         ['class' => 'app\modules\search\Module'],
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
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment

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
    $config['modules']['utility'] = [
        'class' => 'c006\utility\migration\Module',
    ];
}

return $config;