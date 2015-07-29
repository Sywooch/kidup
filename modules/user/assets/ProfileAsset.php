<?php

namespace app\modules\user\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class SettingsAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/user/views/profile/assets';

    public $css = [
        'show.less',
    ];
}
