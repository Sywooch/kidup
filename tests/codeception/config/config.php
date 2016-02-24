<?php
/**
 * Application configuration shared by all test types
 */

$conf = [
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\faker\FixtureController',
            'fixtureDataPath' => '@tests/codeception/fixtures',
            'templatePath' => '@tests/codeception/templates',
            'namespace' => 'codecept\codeception\fixtures',
        ],
    ],
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => (getenv('CIRCLECI') != true) ? 'mysql:host=localhost;dbname=kidup_test' : 'mysql:host=127.0.0.1;dbname=circle_test',
            'username' =>  getenv('CIRCLECI') != true ? 'root' : 'ubuntu',
            'password' =>  getenv('CIRCLECI') != true ? '922nc289b4p2vb8b92b02mcm' : '',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'useFileTransport' => true,
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
    ],
];

return $conf;