<?php
namespace app\modules\search\assets;

use yii\web\AssetBundle;

/**
 * The asset of the search module is used for loading assets.
 *
 * Class ItemAsset
 * @package app\modules\search\assets
 * @author kevin91nl
 */
class SearchAsset extends AssetBundle
{

    public $sourcePath = '@app/modules/search/views/assets';

    public $css = [
        'search.less'
    ];

    public $js = [
        'search/search.module.js',
        'search/search.controller.js'
    ];

    public $depends = [
        '\app\assets\AngularAsset',
        '\yii\web\JqueryAsset',
        '\yii\widgets\PjaxAsset'
    ];

}