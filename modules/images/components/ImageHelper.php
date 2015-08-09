<?php
namespace app\modules\images\components;

use yii\helpers\BaseHtml;
use yii\helpers\Url;

class ImageHelper extends BaseHtml{

    public static function image($filename, $options = [], $htmlOptions = []){

        $url = static::url($filename, $options);

        $htmlOptions['src'] = $url;
        $htmlOptions['style'] = '';
        if(isset($options['w'])){
            $htmlOptions['width'] = $options['w'];
            $htmlOptions['style'] .= 'width:'.$options['w'].';';
        }
        if(isset($options['h'])){
            $htmlOptions['height'] = $options['h'];
            $htmlOptions['style'] .= 'height:'.$options['h'].';';
        }
        if (!isset($htmlOptions['alt'])) {
            $htmlOptions['alt'] = '';
        }
        return static::tag('img', '', $htmlOptions);
    }

    public static function img($filename, $options = [], $htmlOptions = []){
        return static::image($filename, $options, $htmlOptions);
    }

    public static function bgImg($filename, $options = []){
        return "background-image: url('".static::url($filename, $options)."')";
    }

    public static function url($filename, $options = []){
        $isStaticFile = false;
        $folders = explode("/", $filename);
        if($folders[0] == 'kidup'){
            $isStaticFile = true;
        }
        $server = (new ImageManager())->getServer($isStaticFile);

        $folders = explode("/", $filename);

        if($folders[0] == 'kidup'){
            if(YII_ENV !== 'dev'){
                // remove the kidup/ from the filename in prod/staging

                $filename = substr($filename, 6);
            }
            $server->setSourcePathPrefix('/modules/images/images/');
        }else{
            if(YII_ENV == 'dev'){
                $server->setSourcePathPrefix('/runtime/user-uploads/'.ImageManager::createSubFolders($filename));
            }else{
                $server->setSourcePathPrefix('/user-uploads/'.ImageManager::createSubFolders($filename));
            }
        }

        // settings for image
        if(isset($options['q'])){
            $options['fm'] = 'pjpg';
        }
        if(YII_ENV != 'dev'){

            if(!$server->cacheFileExists($filename, $options)){
                $server->makeImage($filename, $options);
            }
            $url = 'https://s3.eu-central-1.amazonaws.com/kidup-images/'.$server->getCachePath($filename, $options);
        }else{
            $url = Url::to(\Yii::$aliases['@web'].'/images/'.$filename.'?'.http_build_query($options), true);
        }

        return $url;
    }
}