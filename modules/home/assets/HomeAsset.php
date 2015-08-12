<?php
namespace app\modules\home\assets;

use yii\web\AssetBundle;

class HomeAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/home/views/home/assets';
    public $js = [
        'js/owl.carousel.min.js',
        'js/typist.js',
        'js/main.js',
    ];

    public $css = [
        'home.less',
    ];

    public $depends = [
        '\yii\web\JqueryAsset'
    ];
}