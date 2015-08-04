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
class ItemSearchAsset extends AssetBundle
{

    public $sourcePath = '@app/modules/search/views/assets';

    public $css = [
        'search.less'
    ];

    public $js = [
        'item/search.module.js',

        // load filters
        'item/filter/query.factory.js',
        'item/filter/location.factory.js',
        'item/filter/categories.factory.js',

        // load main components
        'item/search.controller.js',
        'item/search.factory.js',
        'item/filter.factory.js',
    ];

    public $depends = [
        '\app\assets\AngularAsset',
        '\yii\web\JqueryAsset',
        '\yii\widgets\PjaxAsset'
    ];

}