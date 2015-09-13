<?php

namespace app\assets;
use yii\web\AssetBundle;
/**
 * Asset for the lodash library
 */
class JQueryTextRangeAsset extends AssetBundle
{
    public $sourcePath = '@app/views/assets/js/';
    public $js = [
        'jquery.textrange.js',
    ];
}