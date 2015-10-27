<?php
namespace home\assets;

use app\assets\AppAsset;
use yii\web\AssetBundle;

class HomeAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/home/views/home/assets';

    public $js = [
        'js/owl.carousel.min.js',
        'js/typist.js',
        'js/main.js'
    ];

    public $css = [
        'home.less',
    ];

    public function __construct($config = []){
        $this->depends = [
            AppAsset::className(),
            'search\assets\SearchWidgetAsset'
        ];
        parent::__construct($config);
    }
}