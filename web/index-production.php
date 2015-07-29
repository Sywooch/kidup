<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'prod');
defined('YII_CONSOLE') or define('YII_CONSOLE', false);

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/config.php');

if(is_file(__DIR__ . '/../config/config-production.php')){
    $config = yii\helpers\ArrayHelper::merge($config, require(__DIR__ . '/../config/config-production.php'));
}

(new yii\web\Application($config))->run();
