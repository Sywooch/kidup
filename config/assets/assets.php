<?php
/**
 * Configuration file for the "yii asset" console command.
 */

// In the console environment, some path aliases may not exist. Please define these:
// Yii::setAlias('@webroot', __DIR__ . '/../web');
// Yii::setAlias('@web', '/');

$ret = [
    'bundles' => [
        // order does matter here for the common package to compile correctly
        \yii\web\JqueryAsset::className(),
        \yii\web\YiiAsset::className(),
        \yii\widgets\ActiveFormAsset::className(),
        \yii\validators\ValidationAsset::className(),
        \yii\authclient\widgets\AuthChoiceAsset::className(),
        \kartik\base\WidgetAsset::className(),
        \kartik\form\ActiveFormAsset::className(),
        \kartik\typeahead\TypeaheadHBAsset::className(),
        \home\assets\HomeAsset::className(),
        \item\assets\ItemAsset::className(),
        \kartik\typeahead\TypeaheadAsset::className(),
        \app\assets\AppAsset::className(),
        \review\assets\ReviewScoreAsset::className(),
        \cinghie\cookieconsent\assets\CookieAsset::className(),
    ],
    // Asset bundle for compression output:
    'targets' => [
        'home' => [
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@app/web/release-assets',
            'baseUrl' => '@web/release-assets',
            'js' => 'js/home-{hash}.js',
            'css' => 'css/home-{hash}.css',
            'depends' => [
                'home\assets\HomeAsset',
            ]
        ],
        'common' => [
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@app/web/release-assets',
            'baseUrl' => '@web/release-assets',
            // DO NOT RENAME! (see depencendy in \app\extended\web\View
            'js' => 'js/common-{hash}.js',
            'css' => 'css/common-{hash}.css',
            // catches all leftovers
            'depends' => []
        ],
    ],
    // Asset manager configuration:
    'assetManager' => [
        'basePath' => '@app/web/assets',
        'baseUrl' => '@web/assets',
    ],
    'jsCompressor' => 'uglifyjs {from} -o {to} -c --define >> /dev/null 2>&1',
    'cssCompressor' => 'cleancss -o {to} {from}',
];


return $ret;