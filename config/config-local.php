<?php
$vendorDir = dirname(__DIR__) . '/vendor';
$params = require(__DIR__ . '/params.php');
$keyFile = __DIR__ . '/../config/keys/keys.env';
include_once(__DIR__ . '/keys/load_keys.php'); // sets the var keys

return [
//    'components' => [
//        'db' => [
//            'class' => 'yii\db\Connection',
//            'dsn' => 'mysql:host=kidup-production.c5gkrouylqmw.eu-central-1.rds.amazonaws.com:3306;dbname=kidup',
//            'charset' => 'utf8',
//            'enableSchemaCache' => YII_CACHE,
//            // Duration of schema cache.
//            'schemaCacheDuration' => 3600,
//            // Name of the cache component used to store schema information
//            'schemaCache' => 'cache',
//        ]
//    ]
];
