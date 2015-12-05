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
class SearchPageAsset extends AssetBundle
{

    public $sourcePath = '@search/views/search/assets';

    public $css = [
        'search.less',
        '//cdn.jsdelivr.net/instantsearch.js/1/instantsearch.min.css'
    ];

    public $js = [
        '//cdn.jsdelivr.net/instantsearch.js/1/instantsearch.min.js',
        'http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places',
        'search.js',
        'instantsearch-location.js',
        'jquery.geocomplete.min.js'
    ];

    public $depends = [
        '\app\assets\AppAsset',
        '\yii\web\JqueryAsset',
        '\yii\jui\JuiAsset',
    ];

}