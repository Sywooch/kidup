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
        'app\\modules\\user\\Bootstrap',
        'app\\modules\\mail\\Bootstrap',
        'app\\modules\\message\\Bootstrap',
        'app\\modules\\item\\Bootstrap',
        'app\\modules\\booking\\Bootstrap',
        'app\\modules\\home\\Bootstrap',
        'app\\modules\\pages\\Bootstrap',
        'app\\modules\\admin\\Bootstrap',
    ],
    'controllerNamespace' => 'app\commands',

    'modules' => [
        'gii' => 'yii\gii\Module',
        'gridview' =>       ['class' => '\kartik\grid\Module'],
        'home' =>           ['class' => 'app\modules\home\Module'],
        'item' =>           ['class' => 'app\modules\item\Module'],
        'message' =>        ['class' => 'app\modules\message\Module'],
        'booking' =>        ['class' => 'app\modules\booking\Module'],
        'mail' =>        ['class' => 'app\modules\mail\Module'],
        'pages' =>        ['class' => 'app\modules\pages\Module'],
        'review' =>        ['class' => 'app\modules\review\Module'],
        'admin' =>        ['class' => 'app\modules\admin\Module'],
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
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
        'slack' =>  ['class' => 'app\components\Slack'],
        'logger' => ['class' => 'app\components\Logger'],
        'error' =>  ['class' => 'app\components\Error'],
        'widgetRequest' =>  ['class' => 'app\components\WidgetRequest'],
        'pages' =>  ['class' => 'app\components\Pages'],
        'clog' => ['class' => 'app\components\Log'],
        'sendGrid' => $allComponents['sendGrid'],
        'mailer' => $allComponents['mailer'],
        'urlManager' => [
            'scriptUrl' => '/web'
        ],
    ],

    'params' => $params,
];
