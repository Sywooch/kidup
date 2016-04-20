<?php

namespace app\components\web;

use Yii;
use yii\helpers\Url;
use yii\web\AssetBundle;

class AssetManager extends \yii\web\AssetManager
{
    /**
     * Returns the actual URL for the specified asset.
     * The actual URL is obtained by prepending either [[baseUrl]] or [[AssetManager::baseUrl]] to the given asset path.
     * @param AssetBundle $bundle the asset bundle which the asset file belongs to
     * @param string $asset the asset path. This should be one of the assets listed in [[js]] or [[css]].
     * @return string the actual URL for the specified asset.
     */
    public function getAssetUrl($bundle, $asset)
    {
        if (strpos($asset, '.less') !== false) {
            if (!is_file(str_replace('.less', '.css', $asset))) {
                $this->getConverter()->convert($asset, $bundle->basePath);
            }
            $asset = str_replace('.less', '.css', $asset);
        }
        if (($actualAsset = $this->resolveAsset($bundle, $asset)) !== false) {
            if (strncmp($actualAsset, '@web/', 5) === 0) {
                $asset = substr($actualAsset, 5);
                $basePath = Yii::getAlias("@webroot");
                $baseUrl = Yii::getAlias("@web");
            } else {
                $asset = Yii::getAlias($actualAsset);
                $basePath = $this->basePath;
                $baseUrl = $this->baseUrl;
            }
        } else {
            $basePath = $bundle->basePath;
            $baseUrl = $bundle->baseUrl;
        }

        if (!Url::isRelative($asset) || strncmp($asset, '/', 1) === 0) {
            return $asset;
        }

        if ($this->appendTimestamp && ($timestamp = @filemtime("$basePath/$asset")) > 0) {
            return "$baseUrl/$asset?v=$timestamp";
        } else {
            return "$baseUrl/$asset";
        }
    }
}
