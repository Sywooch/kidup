<?php

namespace app\assets;
use yii\web\AssetBundle;
/**
 * Asset for the lodash library
 */
class SmartBannerAsset extends AssetBundle
{
    public $sourcePath = '@app/views/assets/lib/smartbanner';
    public $js = [
        'smartbanner.js',
    ];
    public $css = [
        'smartbanner.css'
    ];
}