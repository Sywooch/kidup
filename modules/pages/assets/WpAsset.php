<?php
namespace app\modules\pages\assets;

use yii\web\AssetBundle;

class WpAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/pages/views/assets';

    public $css = [
        'wp.css',
    ];

    public $cssOptions = [
        'position' => \yii\web\View::POS_BEGIN
    ];

}