<?php
$vendorDir = dirname(__DIR__) . '/vendor';
$params = require(__DIR__ . '/params.php');
$keyFile = __DIR__ . '/../config/keys.env';
$keys = (new \josegonzalez\Dotenv\Loader($keyFile))->parse()->toArray();

return [
    'sendGrid' => [
        'class' => 'bryglen\sendgrid\Mailer',
        'username' =>  $keys['sendgrid_user'],
        'password' => $keys['sendgrid_password'],
        'viewPath' => '@app/modules/mail/views',
        'useFileTransport' => false
        //'viewPath' => '@app/views/mail', // your view path here
    ],
    'authClientCollection' => [
        'class' => 'yii\authclient\Collection',
        'clients' => [
            'facebook' => [
                'class' => 'yii\authclient\clients\Facebook',
                'clientId' => '1515825585365803',
                'clientSecret' => $keys['facebook_oauth_secret'],
            ],
            'twitter' => [
                'class' => 'yii\authclient\clients\Twitter',
                'consumerKey' => $keys['twitter_oauth_key'],
                'consumerSecret' => $keys['twitter_oauth_secret'],
            ],
        ],
    ],
    'assetManager' => [
        'converter' => [
            'class' => 'yii\web\AssetConverter',
            'commands' => [
                // compile less, minify if in production
                'less' => ['css', 'lessc {from} {to} --no-color'. (YII_ENV == 'prod' ? '-x' : '')],
            ],
        ],
        'assetMap' => [
            // TODO take general cdn libraries here
//            'jquery.js' => '//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js',
//            'jquery.js' => '//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js',
        ],
    ],
    'request' => [
        'cookieValidationKey' => $keys['cookie_validation_key'],
    ],
    'cache' => [
        'class' => 'yii\caching\FileCache',
    ],
    'errorHandler' => [
        'errorAction' => 'site/error',
    ],
    'log' => [
        'traceLevel' => YII_DEBUG ? 3 : 0,
        'targets' => [
            [
                'class' => 'yii\log\FileTarget',
                'levels' => ['error', 'warning'],
            ],
        ],
    ],
    'db' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=localhost;dbname=kidup',
        'username' => $keys['mysql_user'],
        'password' => $keys['mysql_password'],
        'charset' => 'utf8',
    ],
    'urlManager' => [
        'enablePrettyUrl' => true,
        // 'enableStrictParsing' => true,
        'showScriptName' => false,
        'rules' => [
            '/' => YII_ENV == 'dev' || YII_ENV == 'test' || $_SERVER['HTTP_HOST'] == 'beta.kidup.dk' || $_SERVER['HTTP_HOST'] == 'test.kidup.dk' ? 'home/home' : 'splash/default/index',
            '/splash' => 'splash/default/index',
            '/thanks' => 'splash/default/thanks',
            'home' => 'home/home',
            'search' => 'search/search',
            'search-results' => 'search/search/results',
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
            'item/image-upload' => 'item/create/upload',
            'item/image-delete' => 'item/create/delete-upload',
            'item/image-sort' => 'item/create/image-sort',
            'booking/<id:\d+>' => 'booking/view/index',
            'booking/<id:\d+>/confirm' => 'booking/default/confirm',
            'booking/current' => 'booking/list/current',
            'booking/previous' => 'booking/list/previous',
            'booking/by-item/<id:\d+>' => 'booking/list/by-item',
            'booking/<id:\d+>/receipt' => 'booking/view/receipt',
            'booking/<id:\d+>/invoice' => 'booking/view/invoice',
            'booking/<id:\d+>/request' => 'booking/default/request',
            'booking/<id:\d+>/conversation' => 'booking/default/conversation',
            'mail/click' => 'mail/view/link',
            'mail/<id>' => 'mail/view/index',
            'review/create/<bookingId:\d+>' => 'review/create/index',
            'messages/<id:\d+>' => 'message/chat/index',
            'messages' => 'message/chat/index',
            'file/<id>' => 'file/index',
            'conversation/<id:\d+>' => 'message.conversation',
            'p/<page>' => 'pages/default/wordpress',
            'p/<page>/<view>' => 'pages/default/<page>',
        ]
    ],
    'i18n' => [
        'translations' => [
            '*' => [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@app/messages',
            ],
        ],
    ],
    'user' => [
        'identityClass' => 'app\modules\user\models\User', // User must implement the IdentityInterface
        'enableAutoLogin' => true,
    ],
    'keyStore' => ['class' => 'app\components\KeyStore'],
    'slack' => ['class' => 'app\components\Slack'],
    'clog' => ['class' => 'app\components\Log'],
    'widgetRequest' => ['class' => 'app\components\WidgetRequest'],
    'pages' => ['class' => 'app\components\Pages']
];