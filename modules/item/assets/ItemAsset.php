<?php
namespace app\modules\item\assets;

use yii\web\AssetBundle;

class ItemAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/item/views/assets/';

    public $js = [];

    public $css = [
        'itemCard.less'
    ];

    public $depends = [];
}