<?php

namespace user\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class SettingsAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/user/views/settings/assets';

    public $css = [
        'settings.less',
    ];
}
