<?php
/**
 * Configuration file for the "yii asset" console command.
 */

// In the console environment, some path aliases may not exist. Please define these:
// Yii::setAlias('@webroot', __DIR__ . '/../web');
// Yii::setAlias('@web', '/');

return [
    // Adjust command/callback for JavaScript files compressing:
    'jsCompressor' => 'uglifyjs {from} -o {to} -c --define >> /dev/null 2>&1',
    // Adjust command/callback for CSS files compressing:
    'cssCompressor' => 'cleancss -o {to} {from}',
    // The list of asset bundles to compress:
    'bundles' => [
        'app\assets\AppAsset',
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
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
    ],
];