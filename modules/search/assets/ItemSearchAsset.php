<?php
namespace search\assets;

use yii\web\AssetBundle;

/**
 * The asset of the search module is used for loading assets.
 *
 * Class ItemAsset
 * @package \search\assets
 * @author kevin91nl
 */
class ItemSearchAsset extends AssetBundle
{

    public $sourcePath = '@app/modules/search/views/search/assets';

    public $css = [
        'search.less'
    ];

    public $js = [
        'controller.js',
        'infinity.directive.js',
    ];

    public $depends = [
        '\app\assets\AngularAsset',
        '\yii\web\JqueryAsset',
        '\yii\jui\JuiAsset',
        '\yii\widgets\PjaxAsset',
        'search\assets\SearchWidgetAsset'
    ];

}