<?php
namespace app\modules\item\assets;

use yii\web\AssetBundle;

class SearchAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/item/views/search/assets';

    public $css = [
        'search.less',
    ];

    public $js = [
        'search.js'
    ];

    public $depends = [
        '\yii\web\JqueryAsset',
        '\yii\widgets\PjaxAsset',
        '\yii\jui\JuiAsset'
    ];
}