<?php
namespace booking\assets;

use yii\web\AssetBundle;

class ConfirmAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/booking/views/default/assets';

    public $css = [
        'confirm.less',
    ];
}