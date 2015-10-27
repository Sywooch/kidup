<?php
$vendorDir = dirname(__DIR__) . '/vendor';
$params = require(__DIR__ . '/params.php');
include_once(__DIR__ . '/keys/load_keys.php'); // sets the var keys

// if (YII_ENV == 'test') {
$keyFile = __DIR__ . '/keys/keys.env';
if (!file_exists($keyFile)) {
    echo 'php ' . __DIR__ . '/load_keyfile.php';
    exec('php ' . __DIR__ . '/load_keyfile.php');
}
$keys = (new \josegonzalez\Dotenv\Loader($keyFile))->parse()->toArray();
// }
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
            'port' => '25',
//            'encryption' => 'tls',
        ],
        'useFileTransport' => YII_ENV == 'dev' ? true : false,
        'viewPath' => '@app/modules/mail/views',
    ],
    'authClientCollection' => [
        'class' => 'yii\authclient\Collection',
        'clients' => [
            'facebook' => [
                'class' => 'app\extended\auth\Facebook',
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
        ],
    ],
    'view' => [
        'class' => 'app\extended\web\View',
        'renderers' => [
            'twig' => [
                'class' => 'yii\twig\ViewRenderer',
                // set cachePath to false in order to disable template caching
                'cachePath' => '@runtime/Twig/cache',
                // Array of twig options:
                'options' => [
                    'auto_reload' => true,
                ],
                'globals' => [
                    'Image' => 'app/modules/images/widgets/Image'
                ],
            ],
        ],
    ],
    'assetManager' => [
        'class' => 'app\extended\web\AssetManager',
//        'bundles' => (YII_ENV == 'stage' || YII_ENV == 'prod') ? require(__DIR__ . '/assets/assets-prod.php') : [],
        'bundles' => [],
        'converter' => [
            'class' => 'yii\web\AssetConverter',
            'commands' => [
                'less' => [
                    'css',
                    'lessc {from} {to} --no-color -x'
                ],
            ],
        ],

    ],
    'request' => [
        'cookieValidationKey' => $keys['cookie_validation_key'],
        'enableCsrfCookie' => true,
        'enableCsrfValidation' => true,
        'csrfCookie' => ['httpOnly' => true],
        'enableCookieValidation' => true,
    ],
    'cache' => [
        'class' => (YII_CACHE) ? 'yii\caching\ApcCache' : 'yii\caching\DummyCache',
    ],
    'errorHandler' => [
        'errorAction' => 'home/error/error',
    ],
    'log' => [
        'traceLevel' => 5,
        'targets' => [
            'file' => [
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
        'enableSchemaCache' => YII_CACHE,
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
            'sendgrid/webhook-apqcbec' => 'mail/sendgrid/webhook-apqcbec', // sendgrid incomming webhook
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
            ],
            [
                'class' => 'yii\rest\UrlRule',
                'controller' => ['api/v1/conversations' => 'api/conversation'],
            ],
            [
                'class' => 'yii\rest\UrlRule',
                'controller' => ['api/v1/messages' => 'api/message'],
            ],
            [
                'class' => 'yii\rest\UrlRule',
                'controller' => ['api/v1/users' => 'api/user'],
            ],
            [
                'class' => 'yii\rest\UrlRule',
                'controller' => ['api/v1/oauth2' => 'api/oauth2'],
                'extraPatterns' => [
                    'token' => 'token',
                    'refresh' => 'refresh',
                ]
            ],

        ],
    ],
//    'redis' => [
//        'class' => 'yii\redis\Connection',
//        'hostname' => 'localhost',
//        'port' => 6379,
//        'database' => 0,
//    ],
    'i18n' => [
        'translations' => [
            '*' => [
                'class' => 'yii\i18n\DbMessageSource',
                'sourceMessageTable' => 'i18n_source',
                'messageTable' => 'i18n_message',
                'enableCaching' => YII_CACHE,
                'cachingDuration' => YII_CACHE ? 24 * 60 * 60 : 0
            ],
        ],
    ],
    'user' => [
        'identityClass' => 'user\models\User', // User must implement the IdentityInterface
        'enableAutoLogin' => true,
    ],
    'keyStore' => ['class' => 'app\components\KeyStore'],
    'geolocation' => [
        'class' => 'rodzadra\geolocation\Geolocation',
        'config' => [
            'provider' => '[PLUGIN_NAME]',
            'format' => '[SUPORTED_PLUGIN_FORMAT]',
            'api_key' => '[YOUR_API_KEY]',
        ],
    ],
//    'docGenerator' => [
//        'class' => 'eold\apidocgen\src\ApiDocGenerator',
//        'isActive' => true,
//        // Flag to set plugin active
//        'versionRegexFind' => '/\/api\/(\d+)/i',
//        // regex used in preg_replace function to find Yii api version format (usually 'v1', 'vX') ...
//        'versionRegexReplace' => '${2}.0.0',
//        // .. and replace it in Apidoc format (usually 'x.x.x')
//        'docDataAlias' => '@runtime/api_docs'
//        // Folder to save output. make sure is writable.
//    ],
];

if ($keys['yii_env'] == 'test' || YII_ENV == 'test') {
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