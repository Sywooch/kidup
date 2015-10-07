<?php

namespace app\extended\web;

use League\Flysystem\Filesystem;
use Yii;
use yii\helpers\Json;

class AssetManager extends \yii\web\AssetManager
{
    public function registerOriginalsWatcher(){
        $commonPath = 'originals.json';
        $adapter = new \League\Flysystem\Adapter\Local(Yii::$aliases['@app'] . '/web/packages/');
        $filesystem = new Filesystem($adapter);

        if (!$filesystem->has($commonPath)) {
            $filesystem->write($commonPath, Json::encode(['css' => [], 'js' => []]));
        }
        $commonAssets = Json::decode($filesystem->read($commonPath));
        foreach ($this->bundles as $bundle){
            foreach ($bundle->css as $file) {
                if(strpos($bundle->sourcePath, "/vagrant/vendor") > -1 || strpos($bundle->sourcePath, "/vagrant/@bower") > -1){
                    continue;
                }
                $source = str_replace("/vagrant/", '', $bundle->sourcePath).'/'.$file;
                $source = str_replace("@app/", '', $source);
                $commonAssets['css']["web".$bundle->baseUrl.'/'.$file] = $source;
            }
            foreach ($bundle->js as $file) {
                if(strpos($bundle->sourcePath, "/vagrant/vendor") > -1 || strpos($bundle->sourcePath, "/vagrant/@bower") > -1){
                    continue;
                }
                $source = str_replace("/vagrant/", '', $bundle->sourcePath).'/'.$file;
                $source = str_replace("@app/", '', $source);
                $commonAssets['js']["web".$bundle->baseUrl.'/'.$file] = $source;
            }
        }

        $filesystem->update($commonPath, Json::encode($commonAssets));
    }
}
