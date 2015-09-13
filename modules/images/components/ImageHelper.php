<?php
namespace app\modules\images\components;

use app\components\Cache;
use League\Glide\Filesystem\FileNotFoundException;
use yii\helpers\BaseHtml;
use yii\helpers\Json;
use yii\helpers\Url;

class ImageHelper extends BaseHtml
{

    const DEFAULT_USER_FACE = 'kidup/user/default-face.jpg';

    public static function image($filename, $options = [], $htmlOptions = [])
    {

        $function = function () use ($filename, $options, $htmlOptions) {
            $url = static::url($filename, $options);
            if (empty($url) || $url == false) {
                return '';
            }
            $htmlOptions['src'] = $url;

            if(!isset($htmlOptions['style'])){
                $htmlOptions['style'] = '';
            }
            if (isset($options['w'])) {
                if (!isset($htmlOptions['width'])) {
                    $htmlOptions['width'] = $options['w'];
                    $htmlOptions['style'] .= 'width:' . str_replace("px", '', $options['w']) . 'px;';
                }
            }
            if (isset($options['h'])) {
                if (!isset($htmlOptions['height'])) {
                    $htmlOptions['height'] = $options['h'];
                    $htmlOptions['style'] .= 'height:' . str_replace("px", '', $options['h']) . 'px;';
                }
            }
            if (!isset($htmlOptions['alt'])) {
                $htmlOptions['alt'] = '';
            }
            return static::tag('img', '', $htmlOptions);
        };
        return Cache::data('image_' . $filename . Json::encode([$options, $htmlOptions]), $function, 24 * 60 * 60);
    }

    public static function img($filename, $options = [], $htmlOptions = [])
    {
        return static::image($filename, $options, $htmlOptions);
    }

    public static function bgImg($filename, $options = [])
    {
        return "background-image: url('" . ImageHelper::url($filename, $options) . "')";
    }

    public static function urlToFilename($url)
    {
        $expl = explode("---xxx---", str_replace(['?', '/'], '---xxx---', $url ));
        foreach ($expl as $e) {
            if (strpos($e, '.png') !== false || strpos($e, '.jpg') !== false) {
                return $e;
            }
        }
        return false;
    }

    public static function url($filename, $options = [])
    {
        $function = function () use ($filename, $options) {

            if ($filename == false || !is_string($filename)) {
                return '';
            }
            if (YII_ENV == 'test') {
                return "http://placehold.it/2x2";
            }
            $isStaticFile = false;
            $folders = explode("/", $filename);
            if ($folders[0] == 'kidup') {
                $isStaticFile = true;
            }

            $server = (new ImageManager())->getServer($isStaticFile);

            $folders = explode("/", $filename);

            if ($folders[0] == 'kidup') {
                if (YII_ENV !== 'dev') {
                    // remove the kidup/ from the filename in prod/staging
                    $filename = substr($filename, 6);
                }
            } else {
                if (YII_ENV == 'dev') {
                    $server->setSourcePathPrefix(ImageManager::createSubFolders($filename));
                } else {
                    $server->setSourcePathPrefix(ImageManager::createSubFolders($filename));
                }
            }

            // settings for image
            if (isset($options['q'])) {
                $options['fm'] = 'pjpg';
            }
            if (YII_ENV != 'dev') {
                if (!$server->cacheFileExists($filename, $options)) {
                    try{
                        $server->makeImage($filename, $options);
                        $url = 'https://s3.eu-central-1.amazonaws.com/kidup-cache/' . $server->getCachePath($filename, $options);
                    }catch (FileNotFoundException $e){
                        $url = 'http://placehold.it/300x300';
                    }
                }else{
                    $url = 'https://s3.eu-central-1.amazonaws.com/kidup-cache/' . $server->getCachePath($filename, $options);
                }
            } else {
                $url = Url::to(\Yii::$aliases['@web'] . '/images/' . $filename . '?' . http_build_query($options),
                    true);
            }

            return $url;
        };
        return Cache::data('imageurl' . $filename . Json::encode($options), $function, 24 * 60 * 60);
    }
}