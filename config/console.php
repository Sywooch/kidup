<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');
Yii::setAlias('@web', YII_ENV == 'dev' ? '/web' : YII_ENV == 'test' ? '/' : '/'); // required to bootstrap the modules
Yii::setAlias('@assets',
    YII_ENV == 'dev' ? '/web/assets_web' : YII_ENV == 'test' ? '/assets_web' : '/assets_web'); // required to bootstrap the modules
include_once(__DIR__ . '/keys/load_keys.php'); // sets the var keys
$params = require(__DIR__ . '/params.php');
$allComponents = require(__DIR__ . '/components.php');

return [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'gii',
        'user\\Bootstrap',
        'notification\\Bootstrap',
        'message\\Bootstrap',
        'item\\Bootstrap',
        'booking\\Bootstrap',
        'home\\Bootstrap',
        'pages\\Bootstrap',
        'admin\\Bootstrap',
        'api\\Bootstrap',
    ],
    'controllerNamespace' => 'app\commands',

    'modules' => [
        'gii' => 'yii\gii\Module',
        'gridview' => ['class' => '\kartik\grid\Module'],
        'home' => ['class' => '\home\Module'],
        'item' => ['class' => '\item\Module'],
        'message' => ['class' => '\message\Module'],
        'booking' => ['class' => '\booking\Module'],
        'mail' => ['class' => '\mail\Module'],
        'pages' => ['class' => '\pages\Module'],
        'review' => ['class' => '\review\Module'],
        'admin' => ['class' => '\admin\Module'],
        'api' => ['class' => '\api\Module'],
        'user' => [
            'class' => '\user\Module',
            'enableUnconfirmedLogin' => true,
            'confirmWithin' => 365 * 24 * 60 * 60,
            'cost' => 13,
            'admins' => ['admin'],
            'enableConfirmation' => true,
            'enableFlashMessages' => false,
        ],
        'notification' => [
            'class' => '\notification\Module',
            'useFileTransfer' => (YII_ENV == 'test' ? true : false)
        ],
    ],

    'components' => [
        'user' => [
            'class' => 'user\models\user\User', // User must implement the IdentityInterface
        ],
        'cache' => [
            'class' => 'yii\caching\DummyCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\DbTarget',
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
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                ],
            ],
        ],
        'keyStore' => ['class' => 'app\components\KeyStore'],
        'sendGrid' => $allComponents['sendGrid'],
        'mailer' => $allComponents['mailer'],
        'urlManager' => [
            'scriptUrl' => '/web'
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
                        'responsiveImage' => function ($file, $options = []) {
                            return '
                        <!--[if mso]>
                            <img src="' . \images\components\ImageHelper::url($file, $options) . '" alt="Image" width="128">
                            <div style="width:0px; height:0px; overflow:hidden; display:none; visibility:hidden; mso-hide:all;">
                        <![endif]-->
                        
                        <img src="' . \images\components\ImageHelper::url($file, $options) . '" alt="Image" width="100%">
                        
                        <!--[if mso]></div><![endif]-->
                        ';
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
    ],
    'aliases' => [
        '@pages' => '@app/modules/pages',
        '@user' => '@app/modules/user',
        '@notification' => '@app/modules/notification',
        '@home' => '@app/modules/home',
        '@item' => '@app/modules/item',
        '@message' => '@app/modules/message',
        '@images' => '@app/modules/images',
        '@booking' => '@app/modules/booking',
        '@review' => '@app/modules/review',
        '@admin' => '@app/modules/admin',
        '@search' => '@app/modules/search',
        '@api' => '@app/modules/api',
    ],
    'params' => $params,
];
