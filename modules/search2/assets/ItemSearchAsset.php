<?php
namespace app\modules\search2\assets;

use yii\web\AssetBundle;

/**
 * The asset of the search module is used for loading assets.
 *
 * Class ItemAsset
 * @package app\modules\search\assets
 * @author kevin91nl
 */
class ItemSearchAsset extends AssetBundle
{

    public $sourcePath = '@app/modules/search2/views/search/assets';

    public $css = [
        'search.less'
    ];

    public $js = [
        'controller.js',
    ];

    public $depends = [
        '\app\assets\AngularAsset',
        '\yii\web\JqueryAsset',
        '\yii\jui\JuiAsset',
        '\yii\widgets\PjaxAsset'
    ];

}