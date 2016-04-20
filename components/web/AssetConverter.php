<?php
namespace app\components\web;

use Yii;
use yii\web\AssetConverterInterface;


class AssetConverter extends \yii\web\AssetConverter implements AssetConverterInterface
{

    /**
     * Converts a given asset file into a CSS or JS file.
     * @param string $asset the asset file path, relative to $basePath
     * @param string $basePath the directory the $asset is relative to.
     * @return string the converted asset file path, relative to $basePath.
     */
    public function convert($asset, $basePath)
    {
        return str_replace('.less', '.css', $asset);
    }
}
