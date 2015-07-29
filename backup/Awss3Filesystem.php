<?php

namespace app\backup;

use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem as Flysystem;

class Awss3Filesystem implements \BackupManager\Filesystems\Filesystem {

    /**
     * @param $type
     * @return bool
     */
    public function handles($type) {
        return strtolower($type) == 'awss3';
    }

    /**
     * @param array $config
     * @return \League\Flysystem\Filesystem
     */
    public function get(array $config) {
        $client = S3Client::factory([
            'credentials' => [
                'key'    => $config['key'],
                'secret' => $config['secret'],
            ],
            'region' => $config['region'],
            'version' => 'latest',
        ]);

        return new Flysystem(new AwsS3Adapter($client, $config['bucket']));
    }
}
