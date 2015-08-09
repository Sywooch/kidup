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
        "fonts/proxima/proxima.less",
        "fonts/adelle/adelle.less",
        "css/base/typography.less",
        "css/base/misc.less",
        "css/base/buttons.less",
        "css/base/inputs.less",
//        "css/base/sliders.less",
        "css/base/alerts.less",
        "css/base/tables.less",
        "css/base/labels.less",
//        "css/base/tooltips-and-popovers.less",
        "css/base/sections.less",
        "css/base/checkbox-radio-switch.less",
        "css/base/navbars.less",
        "css/base/footers.less",
        "css/base/social-buttons.less",
        "css/base/morphing-buttons.less",
        "css/base/dropdown.less",
        "css/base/icons.less",
        "css/base/tabs-navs-pagination.less",
        "css/base/media.less",
        "css/base/cards.less",
        "css/base/collapse.less",
        "css/base/modal.less",
        "css/base/responsive.less",
        "css/general.less",
        "css/components/_cards.less",
        "css/components/_navbar.less",
        "css/components/_modals.less",
        "css/jquery-ui-1.10.0.custom.less",
        "css/footer.less"
    ];
    public $js = [
        'js/basic.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JQueryAsset',
        'app\assets\FontAwesomeAsset'
    ];
}



