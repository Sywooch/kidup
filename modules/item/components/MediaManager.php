<?php

namespace app\modules\item\components;

use app\models\User;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local as Adapter;
use yii\web\UploadedFile;
use yii;
use yii\imagine\Image;

class MediaManager
{
    const THUMB = '_thumb';
    const ORIGINAL = '_original';
    const MEDIUM = '_medium';
    const DEFAULT_USER_IMG = 'default_user_img.jpg';

    public static function get($file)
    {
        $filesystem = new Filesystem(new Adapter(Yii::$app->params['uploadPath']));
        $dir = '';
        if ($file[0] === "g") {
            $dir .= '/general';
        } else {
            $userId = explode("_", $file)[1];
            $dir .= 'users/' . $userId;
        }
        return $filesystem->read($dir . '/' . $file);
    }

    public static function set(UploadedFile $image, $userId)
    {
        $filesystem = new Filesystem(new Adapter(Yii::$app->params['uploadPath']));
        $dir = '';

        $dir .= 'users/' . $userId;
        $filename = "u_" . $userId . "_" . \Yii::$app->security->generateRandomString();
        $filesystem->createDir($dir);

        // generate a unique file name
        $file = Yii::$app->params['uploadPath'] . $dir . '/' . $filename;
        $image->saveAs($file . MediaManager::ORIGINAL .'.'. $image->extension);

        Image::thumbnail($file .MediaManager::ORIGINAL. "." . $image->extension, 120, 120)
            ->save(Yii::getAlias($file . MediaManager::THUMB .'.'. $image->extension), ['quality' => 80]);

        Image::thumbnail($file .MediaManager::ORIGINAL. "." . $image->extension, 300, 300)
            ->save(Yii::getAlias($file . MediaManager::MEDIUM .'.'. $image->extension), ['quality' => 80]);
        return $filename . "." . $image->extension;
    }

    public static function delete($file)
    {
        $filesystem = new Filesystem(new Adapter(Yii::$app->params['uploadPath']));
        $dir = '';
        if ($file[0] === "g") {
            $dir .= '/general';
        } else {
            $userId = explode("_", $file)[1];
            $dir .= '/users/' . $userId;
        }
        $filesystem->delete(MediaManager::fileversion($dir . '/' . $file, MediaManager::THUMB));
        $filesystem->delete(MediaManager::fileversion($dir . '/' . $file, MediaManager::ORIGINAL));
        $filesystem->delete(MediaManager::fileversion($dir . '/' . $file, MediaManager::MEDIUM));
    }

    public static function fileversion($file, $version)
    {
        return substr_replace(trim($file), $version, count(trim($file)) - 5, 0);
    }

    public static function getUrl($file, $size = MediaManager::ORIGINAL)
    {
        if ($file == MediaManager::DEFAULT_USER_IMG) {
            return yii\helpers\Url::to('@web/img/user/face-0.jpg');
        }
        if(strpos($file, 'placehold.it') > -1){
            return "http://placehold.it/300x300";
        }
        return yii\helpers\Url::to('@web/file/' . substr_replace(trim($file), $size, count(trim($file)) - 5, 0), true);
    }
}