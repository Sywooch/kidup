<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\extended\web;

use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\web\AssetConverterInterface;


class AssetConverter extends Component implements AssetConverterInterface
{

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
