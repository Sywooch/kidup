<?php

namespace app\modules\images\components;

use Aws\S3\S3Client;
use League;
use League\Flysystem\Adapter\Local as Adapter;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;
use yii;
use yii\web\UploadedFile;

/**
 * Class ImageManager
 * @property \League\Flysystem\Filesystem $uploadFilesystem
 * @property \League\Flysystem\Filesystem $cacheFilesystem
 */
class ImageManager
{
    public $uploadFilesystem;
    public $cacheFilesystem;
    private $allowedFormats = ['jpg', 'png', 'jpeg', 'pjpg', 'gif'];

    public function __construct()
    {
        $this->setFileSystems();
    }

    /**
     * Returns the filesystem for S3 caching bucket
     * @return Filesystem
     */
    private function getS3CacheFilesystem()
    {
        $client = new S3Client([
            'credentials' => [
                'key' => \Yii::$app->keyStore->get('aws_s3_upload_access'),
                'secret' => \Yii::$app->keyStore->get('aws_s3_upload_secret'),
            ],
            'region' => 'eu-central-1',
            'version' => 'latest',
        ]);
        $adapter = new AwsS3Adapter($client, 'kidup-cache');

        $fs = new Filesystem($adapter);
        $fs->createDir('test');

        return $fs;
    }

    /**
     * Returns the filesystem for S3 uploading bucket
     * @return Filesystem
     */
    private function getS3UploadFilesystem()
    {
        $client = new S3Client([
            'credentials' => [
                'key' => \Yii::$app->keyStore->get('aws_s3_upload_access'),
                'secret' => \Yii::$app->keyStore->get('aws_s3_upload_secret'),
            ],
            'region' => 'eu-central-1',
            'version' => 'latest',
        ]);
        $adapter = new AwsS3Adapter($client, 'kidup-user-content');

        return new Filesystem($adapter);
    }

    private function setFileSystems()
    {
        if (YII_ENV == 'prod' || YII_ENV == 'stage') {
            $this->cacheFilesystem = $this->getS3CacheFilesystem();
            $this->uploadFilesystem = $this->getS3UploadFilesystem();
        } else {
            // dev
            $files = new Filesystem(new Adapter(\Yii::$aliases['@runtime']));
            $files->createDir('user-uploads');
            $files->createDir('cache');
            $this->cacheFilesystem = new Filesystem(new Adapter(\Yii::$aliases['@runtime'] . '/cache'));
            $this->uploadFilesystem = new Filesystem(new Adapter(\Yii::$aliases['@runtime'] . '/user-uploads'));
        }

        return true;
    }

    public static function createSubFolders($filename, $depth = 3)
    {
        $folder = [];
        for ($i = $depth; $depth > 0; $depth--) {
            $folder[] = substr($filename, 0, 1);
            $filename = substr($filename, 1);
        }
        return implode("/", $folder);
    }

    public function upload(UploadedFile $image)
    {
        // generate a unique file name

        $filename = strtolower(str_replace('-', '0', str_replace("_", '-', \Yii::$app->security->generateRandomString())));
        $dir = static::createSubFolders($filename);
        $this->uploadFilesystem->createDir($dir);

        $tmpFile = Yii::$aliases['@runtime'] . "/" . $filename;
        $image->saveAs($tmpFile);
        $this->uploadFilesystem->write($dir . '/' . $filename . "." . $image->extension, file_get_contents($tmpFile));
        unlink($tmpFile);
        return $filename . "." . $image->extension;
    }

    /**
     * Function for internally storing images
     * @param string $imgData
     */
    public function store($imgData, $originalName)
    {
        $filename = strtolower(str_replace('-', '0',
            str_replace("_", '-', \Yii::$app->security->generateRandomString())));
        $dir = static::createSubFolders($filename);
        $this->uploadFilesystem->createDir($dir);
        if (strpos($originalName, ".") === false) {
            // no file extension
            $extension = '';
        } else {
            $extension = strrev(explode('.', strrev($originalName))[0]);
            if (!in_array($extension, $this->allowedFormats)) {
                // this format is not allowed
                return false;
            }
        }

        $this->uploadFilesystem->write($dir . '/' . $filename . "." . $extension, $imgData);
        return $filename . "." . $extension;
    }

    public function delete($filename)
    {
        // we don't delete content,nananana
        return false;
    }

    public function getServer($isStatic = false)
    {
        $cache = $this->cacheFilesystem;

        if ($isStatic) {
            $source = new Filesystem(new Adapter(\Yii::$aliases['@app'] . '/modules/images/images/')); // souce is always local, push to S3 in production environvment
        } else {
            $source = $this->uploadFilesystem;
        }

        $imageManager = new \Intervention\Image\ImageManager([
            'driver' => 'imagick',
        ]);

        $manipulators = [
            new League\Glide\Manipulators\Orientation(),
            new League\Glide\Manipulators\Crop(),
            new League\Glide\Manipulators\Size(2000 * 2000),
            new League\Glide\Manipulators\Brightness(),
            new League\Glide\Manipulators\Contrast(),
            new League\Glide\Manipulators\Gamma(),
            new League\Glide\Manipulators\Sharpen(),
            new League\Glide\Manipulators\Filter(),
            new League\Glide\Manipulators\Blur(),
            new League\Glide\Manipulators\Pixelate(),
            new League\Glide\Manipulators\Background(),
            new League\Glide\Manipulators\Border(),
            new League\Glide\Manipulators\Encode(),
        ];

        $api = new League\Glide\Api\Api($imageManager, $manipulators);
        $server = new League\Glide\Server(
            $source,
            $cache,
            $api
        );

        return $server;
    }
}