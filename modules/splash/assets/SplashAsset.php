<?php

namespace app\modules\splash\assets;

use yii\web\AssetBundle;

class SplashAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/splash/views/default/assets';

    public $css = [
        'splash.less',
    ];
}
