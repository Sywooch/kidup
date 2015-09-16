<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 */
class FullModalAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootstrap-modal-carousel/dist';
    public $css = [
        'css/bootstrap-modal-carousel.min.css'
    ];
    public $js = [
        'js/bootstrap-modal-carousel.min.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}



