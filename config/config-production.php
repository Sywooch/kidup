<?php
$vendorDir = dirname(__DIR__) . '/vendor';
$params = require(__DIR__ . '/params.php');
$keyFile = __DIR__ . '/../config/keys.env';
$keys = (new \josegonzalez\Dotenv\Loader($keyFile))->parse()->toArray();
return [
    'components' => [
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.sendgrid.net',
                'username' =>  $keys['sendgrid_user'],
                'password' => $keys['sendgrid_password'],
                'port' => '587',
                'encryption' => 'tls',
            ],
            'useFileTransport' => false,
            'viewPath' => '@app/modules/mail/views',
        ],
    ],
];
