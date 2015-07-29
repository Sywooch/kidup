<?php

$keyFile = __DIR__ . '/../keys.env';
$keys = (new \josegonzalez\Dotenv\Loader($keyFile))->parse()->toArray();
return [
    'local' => [
        'type' => 'Local',
        'root' => \Yii::$aliases['@runtime'].'/backups/',
    ],
    's3' => [
        'type' => 'AwsS3',
        'key'    => $keys['aws_backup_manager_access'],
        'secret' => $keys['aws_backup_secret'],
        'region' => 'eu-central-1',
        'bucket' => 'kidup-backups',
    ],
];