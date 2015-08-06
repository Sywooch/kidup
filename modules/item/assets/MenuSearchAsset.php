<?php
namespace app\modules\item\assets;

use yii\web\AssetBundle;

class MenuSearchAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/item/widgets/views/assets/';

    public $js = [
        'menuSearch.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset'
    ];
}