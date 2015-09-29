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
}
