<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $sourcePath = '@app/views/assets';
    public $css = [
        "css/general.less",
        "css/components/_cards.less",
        "css/components/_navbar.less",
        "css/components/_modals.less",
        "css/footer.less"
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JQueryAsset',
        'app\assets\FontAwesomeAsset',
        'app\assets\GsdkAsset'
    ];
}



