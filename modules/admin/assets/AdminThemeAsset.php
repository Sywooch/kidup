<?php
namespace app\modules\admin\assets;
use yii\base\Exception;
use yii\web\AssetBundle;
/**
 * AdminLte AssetBundle
 * @since 0.1
 */
class AdminThemeAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/admin/views/assets/adminlte';
    public $css = [
        'css/AdminLTE.min.css',
        'css/skins/_all-skins.min.css'
    ];
    public $js = [
        'js/app.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'app\assets\FontAwesomeAsset',
    ];
}