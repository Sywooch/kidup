<?php

namespace app\extended\web;

use Yii;

class AssetManager extends \yii\web\AssetManager
{
    /**
     * Dont do anything with the assets accept for returning their pathnames, View will handle the rest
     * @param string $path
     * @param array $options
     * @return array
     */
    public function publish($path, $options = [])
    {
        $path = Yii::getAlias($path);
        return [
            $path,
            $path
        ];
    }

    /**
     * Converts a given asset file into a CSS or JS file.
     * @param string $asset the asset file path, relative to $basePath
     * @param string $basePath the directory the $asset is relative to.
     * @return string the converted asset file path, relative to $basePath.
     */
    public function convert($asset, $basePath)
    {
        return $asset;
    }
}
