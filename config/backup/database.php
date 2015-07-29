<?php
$keyFile = __DIR__ . '/../keys.env';
$keys = (new \josegonzalez\Dotenv\Loader($keyFile))->parse()->toArray();
return [
    'beta' => [
        'type' => 'mysql',
        'host' => 'localhost',
        'port' => '3306',
        'user' => $keys['mysql_user'],
        'pass' => $keys['mysql_password'],
        'database' => 'kidup',
    ],
];
