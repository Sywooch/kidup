<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');
Yii::setAlias('@web', YII_ENV == 'dev' ? '/web' : YII_ENV == 'test' ? '/' : '/'); // required to bootstrap the modules
Yii::setAlias('@assets', YII_ENV == 'dev' ? '/web/assets_web' : YII_ENV == 'test' ? '/assets_web' : '/assets_web'); // required to bootstrap the modules
include_once (__DIR__ . '/keys/load_keys.php'); // sets the var keys
$params = require(__DIR__ . '/params.php');
$allComponents = require(__DIR__ . '/components.php');

return [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'gii',
        'user\\Bootstrap',
        'mail\\Bootstrap',
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
        'gridview' =>       ['class' => '\kartik\grid\Module'],
        'home' =>           ['class' => '\home\Module'],
        'item' =>           ['class' => '\item\Module'],
        'message' =>        ['class' => '\message\Module'],
        'booking' =>        ['class' => '\booking\Module'],
        'mail' =>        ['class' => '\mail\Module'],
        'pages' =>        ['class' => '\pages\Module'],
        'review' =>        ['class' => '\review\Module'],
        'admin' =>        ['class' => '\admin\Module'],
        'api' =>        ['class' => '\api\Module'],
        'user' => [
            'class' => '\user\Module',
            'enableUnconfirmedLogin' => true,
            'confirmWithin' => 365 * 24 * 60 * 60,
            'cost' => 13,
            'admins' => ['admin'],
            'enableConfirmation' => true,
            'enableFlashMessages' => false,
        ],
    ],

    'components' => [
        'user' => [
            'class' => 'user\models\User', // User must implement the IdentityInterface
        ],
        'cache' => [
            'class' => 'yii\caching\ApcCache',
        ],
        'log' => [
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
