<?php
namespace app\assets;

use yii\web\AssetBundle;

class DropZoneAsset extends AssetBundle
{

    public $sourcePath = '@bower/dropzone/dist';
    public $css = [
        'dropzone.css',
        'basic.css',
    ];
    public $js = [
        'dropzone.js'
    ];

    public function init()
    {
        parent::init();
    }
}