<?php
namespace app\modules\item\assets;

use yii\web\AssetBundle;

class ItemAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/item/widgets/views/assets';

    public $js = [];

    public $css = [
        'itemCard.less'
    ];

    public $depends = [];
}