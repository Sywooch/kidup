<?php
namespace search\assets;

use yii\web\AssetBundle;

/**
 * The asset of the search widget module is used for loading assets.
 *
 * Class SearchWidget
 * @package \search\assets
 * @author kevin91nl
 */
class SearchWidgetAsset extends AssetBundle
{

    public $sourcePath = '@app/modules/search/views/widget/assets';

    public $js = [
        'search_widget.js',
    ];

    public $depends = [];

}