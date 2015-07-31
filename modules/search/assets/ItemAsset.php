<?php
namespace app\modules\search\assets;

use yii\web\AssetBundle;

/**
 * The item asset of the search module is used for loading assets related to searching items.
 *
 * Class ItemAsset
 * @package app\modules\search\assets
 * @author kevin91nl
 */
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