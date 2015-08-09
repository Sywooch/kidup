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
        $server = (new ImageManager())->getServer();

        $folders = explode("/", $filename);

        if($folders[0] == 'kidup'){
            $server->setSourcePathPrefix('/modules/images/images/');
        }else{
            $server->setSourcePathPrefix('/runtime/user-uploads/'.ImageManager::createSubFolders($filename));
        }

        // settings for image
        if(isset($options['q'])){
            $options['fm'] = 'pjpg';
        }
        // remove the kidup/ from the filename in prod/staging
        if(YII_ENV != 'dev'){
            $filename = substr($filename, 6);
        }

        if($server->cacheFileExists($filename, $options) && YII_ENV != 'dev'){
            $url = 'https://s3.eu-central-1.amazonaws.com/kidup-images/'.$server->getCachePath($filename, $options);
        }else{
            $url = Url::to(\Yii::$aliases['@web'].'/images/'.$filename.'?'.http_build_query($options), true);
        }

        return $url;
    }
}