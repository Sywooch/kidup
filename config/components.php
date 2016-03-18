<?php
$vendorDir = dirname(__DIR__) . '/vendor';
$params = require(__DIR__ . '/params.php');
$urls = require(__DIR__ . '/urls.php');
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
        'viewPath' => '@app/modules/notifications/views',
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
        'useFileTransport' => YII_ENV == 'dev' || YII_ENV == 'prod' ? false : false,
        'viewPath' => '@app/modules/notifications/views',
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
                'class' => 'app\extended\web\TwigRenderer',
                // set cachePath to false in order to disable template caching
                'cachePath' => '@runtime/Twig/cache',
                // Array of twig options:
                'options' => [
                    'debug' => true,
                    'auto_reload' => true,
                ],
                'extensions' => [
                    '\Twig_Extension_Debug',
                ],
                'globals' => [
                    'Image' => 'app/modules/images/widgets/Image',
                    'urlHelper' => 'app/components/UrlHelper'
                ],
                'functions' => [
                    't' => function ($cat, $default, $params = []) {
                        return \Yii::t($cat, $default, $params);
                    },
                    'userIsGuest' => function () {
                        return \Yii::$app->user->isGuest;
                    },
                    'image' => function ($file, $options, $htmlOptions = []) {
                        return \images\components\ImageHelper::image($file, $options, $htmlOptions);
                    },
                    'imageUrl' => function ($file, $options = []) {
                        return \images\components\ImageHelper::url($file, $options);
                    },
                    'bgImage' => function ($file, $options = []) {
                        return \images\components\ImageHelper::bgCoverImg($file, $options);
                    },
                    'timestampToDate' => function ($timestamp) {
                        Carbon\Carbon::setToStringFormat("d-m-y");
                        return Carbon\Carbon::createFromTimestamp($timestamp);
                    },
                    'url' => function ($url) {
                        return \yii\helpers\Url::to("@web/" . $url, true);
                    },
                    'now' => function () {
                        return date('d-m-y H:i');
                    },
                    'setTitle' => function ($viewModel, $title) {
                        return $viewModel->title = \app\helpers\ViewHelper::getPageTitle($title);
                    },
                    'base64_image' => function ($file) {
                        $path = \Yii::getAlias($file);
                        $data = file_get_contents($path);
                        $type = pathinfo($path, PATHINFO_EXTENSION);
                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                        return $base64;
                    },
                    'base64_font' => function ($file) {
                        $data = \Yii::$app->view->renderFile($file);
                        return 'data:application/octet-stream;base64,' . base64_encode($data);
                    },
                    'load_file' => function ($file) {
                        $path = \Yii::getAlias($file);
                        return \Yii::$app->view->renderFile($path);
                    }
                ]
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
        'parsers' => [
            'application/json' => 'yii\web\JsonParser',
        ],
        'class' => '\yii\web\Request',
    ],
    'cache' => [
        'class' => 'yii\caching\DummyCache',
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
        'rules' => $urls
    ],
    'response' => [
        'class' => 'yii\web\Response',
        'on beforeSend' => function ($event) {
            // auto adds access control if an api request
            if (strpos(\Yii::$app->request->url, "/api/") !== false) {
                \Yii::$app->response->headers->set("Access-Control-Allow-Origin", "*");
                // change all bad requests to success: false, data: details format
                $response = $event->sender;
                if ($response->statusCode >= 400) {
                    $response->data = [
                        'success' => $response->isSuccessful,
                        'data' => $response->data,
                    ];
                }
            }
        },
    ],
    'i18n' => [
        'translations' => [
            '*' => [
                'class' => 'yii\i18n\DbMessageSource',
                'sourceMessageTable' => 'i18n_source',
                'messageTable' => 'i18n_message',
                'enableCaching' => YII_CACHE,
                'cachingDuration' => YII_CACHE ? 24 * 60 * 60 : 0,
                'on missingTranslation' => ['app\components\TranslationEventHandler', 'handleMissingTranslation']
            ],
        ],
    ],
    'user' => [
        'identityClass' => 'user\models\User', // User must implement the IdentityInterface
        'enableAutoLogin' => true,
    ],
    'keyStore' => ['class' => 'app\components\KeyStore'],
    'urlHelper' => ['class' => 'app\components\UrlHelper'],
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
    // solving too many mysql connections errors bug during testing
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