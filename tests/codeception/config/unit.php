<?php
/**
 * Application configuration for acceptance tests
 */
return yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../../config/config.php'),
    require(__DIR__ . '/../../../config/config-local.php'),
    require(__DIR__ . '/config.php'),
    [

    ]
);
