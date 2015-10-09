<?php

namespace app\extended\web;

use League\Flysystem\Filesystem;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\AssetBundle;

class AssetManager extends \yii\web\AssetManager
{
    public function registerOriginalsWatcher()
    {
        $commonPath = 'originals.json';
        $adapter = new \League\Flysystem\Adapter\Local(Yii::$aliases['@app'] . '/web/packages/');
        $filesystem = new Filesystem($adapter);

        if (!$filesystem->has($commonPath)) {
            $filesystem->write($commonPath, Json::encode(['css' => [], 'js' => []]));
        }
        $commonAssets = Json::decode($filesystem->read($commonPath));
        foreach ($this->bundles as $bundle) {
            if (isset($bundle->css)) {
                foreach ($bundle->css as $file) {
                    if (strpos($bundle->sourcePath, "/vagrant/vendor") > -1 || strpos($bundle->sourcePath,
                            "/vagrant/@bower") > -1
                    ) {
                        continue;
                    }
                    $source = str_replace("/vagrant/", '', $bundle->sourcePath) . '/' . $file;
                    $source = str_replace("@app/", '', $source);
                    $commonAssets['css']["web" . $bundle->baseUrl . '/' . $file] = $source;
                }
            }
            if (isset($bundle->js)) {
                foreach ($bundle->js as $file) {
                    if (strpos($bundle->sourcePath, "/vagrant/vendor") > -1 || strpos($bundle->sourcePath,
                            "/vagrant/@bower") > -1
                    ) {
                        continue;
                    }
                    $source = str_replace("/vagrant/", '', $bundle->sourcePath) . '/' . $file;
                    $source = str_replace("@app/", '', $source);
                    $commonAssets['js']["web" . $bundle->baseUrl . '/' . $file] = $source;
                }
            }
        }
        $filesystem->update($commonPath, Json::encode($commonAssets));
    }

    /**
     * Returns the actual URL for the specified asset.
     * The actual URL is obtained by prepending either [[baseUrl]] or [[AssetManager::baseUrl]] to the given asset path.
     * @param AssetBundle $bundle the asset bundle which the asset file belongs to
     * @param string $asset the asset path. This should be one of the assets listed in [[js]] or [[css]].
     * @return string the actual URL for the specified asset.
     */
    public function getAssetUrl($bundle, $asset)
    {
        $asset = str_replace('.less', '.css', $asset);
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
