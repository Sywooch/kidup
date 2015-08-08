<?php

namespace app\modules\images\components;

use League\Flysystem\Adapter\Local as Adapter;
use yii\web\UploadedFile;
use yii;
use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League;
use League\Flysystem\Filesystem;
/*
* @var League\Flysystem\Filesystem $filesystem
*/

class ImageManager
{
    /*
    * @var League\Flysystem\Filesystem $filesystem
    */
    public $filesystem;
    public $server;

    public function __construct()
    {
        $this->setFileSystem();
    }

    private function setFileSystem()
    {
        if (!empty($this->filesystem)) {
            return $this->filesystem;
        }
        if (YII_ENV == 'prod' || YII_ENV == 'stage') {
            $client = new S3Client([
                'credentials' => [
                    'key' => \Yii::$app->keyStore->get('aws_images_access'),
                    'secret' => \Yii::$app->keyStore->get('aws_images_secret'),
                ],
                'region' => \Yii::$app->keyStore->get('aws_images_region'),
                'version' => 'latest',
            ]);
            $adapter = new AwsS3Adapter($client, \Yii::$app->keyStore->get('aws_images_bucket'));

        } else {
            // dev, stage, testing
            $adapter = new Adapter(\Yii::$aliases['@app']);
        }

        $this->filesystem = new Filesystem($adapter);
        return $this->filesystem;
    }

    public static function createSubFolders($filename, $depth = 2)
    {
        $folder = [];
        for ($i = $depth; $depth > 0; $depth--) {
            $folder[] = substr($filename, 0, 3);
            $filename = substr($filename, 3);
        }
        return implode("/", $folder);
    }

    public function upload(UploadedFile $image)
    {
        // generate a unique file name

        $filename = \Yii::$app->security->generateRandomString();
        $dir = '';
        if(YII_ENV == 'dev'){
            $dir = 'runtime/';
        }
        $dir .= 'user-uploads/' . static::createSubFolders($filename);
        $this->filesystem->createDir($dir);

        $tmpFile = Yii::$aliases['@runtime'] . "/" . $filename;
        $image->saveAs($tmpFile);
        $this->filesystem->write($dir . '/' . $filename . "." . $image->extension, file_get_contents($tmpFile));
        unlink($tmpFile);
        return $filename . "." . $image->extension;
    }

    public function delete($filename)
    {
        $dir = 'user/' . static::createSubFolders($filename);
        $this->filesystem->delete($dir);
    }

    public function getServer()
    {
        $source = new Filesystem(new Adapter(\Yii::$aliases['@app'])); // souce is always local, push to S3 in production environvment

        if(YII_ENV == 'dev'){
            $cache = new Filesystem(new Adapter(\Yii::$aliases['@runtime'].'/cache/images'));
        }else{
            $cache = $this->filesystem;
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

        if(YII_ENV !== 'dev'){
            $server->setCachePathPrefix('/cache/');
        }

        return $server;
    }
}