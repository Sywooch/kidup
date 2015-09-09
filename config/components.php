<?php
$vendorDir = dirname(__DIR__) . '/vendor';
$params = require(__DIR__ . '/params.php');
include_once(__DIR__ . '/keys/load_keys.php'); // sets the var keys

if (YII_ENV == 'test') {
    $keyFile = __DIR__ . '/keys/keys.env';
    if (!file_exists($keyFile)) {
        echo 'php ' . __DIR__ . '/load_keyfile.php';
        exec('php ' . __DIR__ . '/load_keyfile.php');
    }
    $keys = (new \josegonzalez\Dotenv\Loader($keyFile))->parse()->toArray();
}
$components = [
    'session' => [
        'class' => 'yii\web\DbSession'
    ],
    'sendGrid' => [
        'class' => 'bryglen\sendgrid\Mailer',
        'username' => $keys['sendgrid_user'],
        'password' => $keys['sendgrid_password'],
        'viewPath' => '@app/modules/mail/views',
        'useFileTransport' => YII_ENV == 'dev' ? true : false
    ],
    'mailer' => [
        'class' => 'yii\swiftmailer\Mailer',
        'transport' => [
            'class' => 'Swift_SmtpTransport',
            'host' => 'smtp.sendgrid.net',
            'username' => $keys['sendgrid_user'],
            'password' => $keys['sendgrid_password'],
            'port' => '587',
//            'encryption' => 'tls',
        ],
        'useFileTransport' => YII_ENV == 'dev' ? true : false,
        'viewPath' => '@app/modules/mail/views',
    ],
    'authClientCollection' => [
        'class' => 'yii\authclient\Collection',
        'clients' => [
            'facebook' => [
                'class' => 'yii\authclient\clients\Facebook',
                'clientId' => '1515825585365803',
                'clientSecret' => $keys['facebook_oauth_secret'],
                'viewOptions' => ['popupWidth' => 800, 'popupHeight' => 500],
                'attributeNames' => [
                    'email',
                    'name',
                    'picture',
                    'friends'
                ]
            ],
            'twitter' => [
                'class' => 'yii\authclient\clients\Twitter',
                'consumerKey' => $keys['twitter_oauth_key'],
                'consumerSecret' => $keys['twitter_oauth_secret'],
            ],
        ],
    ],
    'view'         => [
        'class' => 'app\components\extended\View',
    ],
    'assetManager' => [
        'converter' => [
            'class' => 'yii\web\AssetConverter',
            'commands' => [
                // compile less, minify if in production
                'less' => [
                    'css',
                    'lessc {from} {to} --no-color ' . ((YII_ENV == 'prod' || YII_ENV == 'stage') ? '-x' : '')
                ],
            ],
        ],
        'bundles' => require(__DIR__ . '/assets/' . ((YII_ENV == 'prod' || YII_ENV == 'stage') ? 'assets-prod.php' : 'assets.php')),
    ],
    'request' => [
        'cookieValidationKey' => $keys['cookie_validation_key'],
    ],
    'cache' => [
        'class' => ( YII_CACHE == true) ? 'yii\caching\ApcCache' : 'yii\caching\DummyCache',
    ],
    'errorHandler' => [
        'errorAction' => 'site/error',
    ],
    'log' => [
        'traceLevel' => YII_DEBUG ? 3 : 0,
        'targets' => [
            [
                'class' => 'yii\log\SyslogTarget',
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
        'enableSchemaCache' => true,
        // Duration of schema cache.
        'schemaCacheDuration' => 3600,
        // Name of the cache component used to store schema information
        'schemaCache' => 'cache',
    ],
    'urlManager' => [
        'enablePrettyUrl' => true,
        // 'enableStrictParsing' => true,
        'showScriptName' => false,
        'rules' => [
            '/' => 'home/home',
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
            'images/<id>' => 'images/index',
            'images/<folder1>/<id>' => 'images/index',
            'images/<folder1>/<folder2>/<id>' => 'images/index',
            'images/<folder1>/<folder2>/<folder3>/<id>' => 'images/index',
            'conversation/<id:\d+>' => 'message.conversation',
            'p/<page>' => 'pages/default/wordpress',
            'p/<page>/<view>' => 'pages/default/<page>',
        ],
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
    'pages' => ['class' => 'app\components\Pages'],
    'geolocation' => [
        'class' => 'rodzadra\geolocation\Geolocation',
        'config' => [
            'provider' => '[PLUGIN_NAME]',
            'format' =>  '[SUPORTED_PLUGIN_FORMAT]',
            'api_key' => '[YOUR_API_KEY]',
        ],
    ]
];

if($keys['yii_env'] == 'test' || YII_ENV == 'test'){
    // solving too many mysql connectsions errors bug during testing
    //https://github.com/Codeception/Codeception/issues/1363
    $components['db']['attributes'] = [
        PDO::ATTR_PERSISTENT => true
    ];
    $components['authClientCollection']['clients']['facebook'] = [
        'class' => 'yii\authclient\clients\Facebook',
        'clientId' => '1515825585365803',
        'clientSecret' => $keys['facebook_oauth_secret']
    ];
}


return $components;