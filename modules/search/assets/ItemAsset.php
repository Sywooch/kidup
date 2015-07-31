<?php
namespace app\modules\search\assets;

use yii\web\AssetBundle;

class ItemAsset extends AssetBundle
{

    public $sourcePath = '@app/modules/search/views/assets';

    public $css = [
        'item.less'
    ];

    public $js = [
        'item.js'
    ];

    public $depends = [
        '\yii\web\JqueryAsset',
        '\yii\widgets\PjaxAsset'
    ];

}