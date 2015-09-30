<?php
namespace item\assets;

use yii\web\AssetBundle;

class ListAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/item/views/list/assets';

    public $css = [
        'list.less',
    ];
}