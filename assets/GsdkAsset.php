<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class GsdkAsset extends AssetBundle
{
    public $sourcePath = '@app/views/assets';
    public $css = [
        "fonts/adelle/adelle.less",
        "css/jquery-ui-1.10.0.custom.less",
        "css/footer.less",
        "css/style.less",
    ];
    public $js = [
        "js/basic.js"
    ];
}



