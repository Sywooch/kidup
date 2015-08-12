<?php
namespace app\modules\item\assets;

use yii\web\AssetBundle;

class ViewAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/item/views/view/assets';

    public $css = [
        'view.less',
    ];

    public $js = [
        'ie-fix.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset'
    ];
}