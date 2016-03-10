<?php
$_SERVER['SCRIPT_FILENAME'] = dirname(dirname(__DIR__)) . '/../web/index-test.php';
$_SERVER['SCRIPT_NAME'] = YII_TEST_ENTRY_URL;
/**
 * Application configuration for functional tests
 */
return yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../../config/config.php'),
    require(__DIR__ . '/config.php'),
    [
        'components' => [
            'request' => [
                // it's not recommended to run functional tests with CSRF validation enabled
                'enableCsrfValidation' => false,
                'class' => '\yii\web\Request',
//                 but if you absolutely need it set cookie domain to localhost
                /*
                'csrfCookie' => [
                    'domain' => 'localhost',
                ],
                */
            ],
        ],
    ]
);
