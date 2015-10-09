<?php
/**
 * Configuration file for the "yii asset" console command.
 */

// In the console environment, some path aliases may not exist. Please define these:
// Yii::setAlias('@webroot', __DIR__ . '/../web');
// Yii::setAlias('@web', '/');

$ret = [
    'bundles' => [
//    'app\assets\AppAsset',
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
        'home\assets\HomeAsset',
    ],
    // Asset bundle for compression output:
    'targets' => [
        'all' => [
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@app/web/release-assets',
            'baseUrl' => '@web/release-assets',
            'js' => 'js/all-{hash}.js',
            'css' => 'css/all-{hash}.css',
        ],
    ],
    // Asset manager configuration:
    'assetManager' => [
        'basePath' => '@app/web/assets',
        'baseUrl' => '@web/assets',
    ]
];

//if(YII_ENV != 'dev' || YII_CONSOLE){
$ret['jsCompressor'] = 'uglifyjs {from} -o {to} -c --define >> /dev/null 2>&1';
$ret['cssCompressor'] = 'cleancss -o {to} {from}';
//}

return $ret;