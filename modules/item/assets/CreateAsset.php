<?php
namespace app\modules\item\assets;

use yii\web\AssetBundle;

class CreateAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/item/views/create/assets';

    public $css = [
        'create.less',
    ];

    public $js = [
        'edit.js'
    ];

    public $depends = [
        '\yii\web\JqueryAsset',
        '\yii\jui\JuiAsset',
        '\app\assets\DropZoneAsset'
    ];
}