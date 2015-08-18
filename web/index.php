<?php
// comment out the following two lines when deployed to production
defined('YII_CONSOLE') or define('YII_CONSOLE', false); // bool
require(__DIR__ . '/../vendor/autoload.php');

include_once (__DIR__ . '/../config/keys/load_keys.php'); // sets the var keys

defined('YII_DEBUG') or define('YII_DEBUG', $keys['yii_debug']); // bool
defined('YII_ENV') or define('YII_ENV', $keys['yii_env']); // dev, test, prod, stage
defined('YII_CACHE') or define('YII_CACHE', $keys['yii_cache']); // use caching

require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/config.php');

if(is_file(__DIR__ . '/../config/config-local.php')){
    $config = yii\helpers\ArrayHelper::merge($config, require(__DIR__ . '/../config/config-local.php'));
}

(new yii\web\Application($config))->run();
