<?php

namespace app\backup;
use Aws\S3\S3Client;
use League\Flysystem\Adapter\Local;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Config;
use League\Flysystem\Filesystem;

/**
 * Responsible for backing up the database
 * Class Database
 * @package app\backup
 */
class File implements BackupInterface{

    private $local;
    private $s3;
    private $folders = [
        'uploads',
        'runtime/logs',
        'runtime/received_mails',
        'runtime/payout_exports'
    ];
    public function __construct(){
        $this->local = new Filesystem(new Local(\Yii::$aliases['@app']));
        $this->s3 = new AwsS3Adapter(S3Client::factory([
            'credentials' => [
                'key'    => \Yii::$app->keyStore->get('aws_backup_manager_access'),
                'secret' => \Yii::$app->keyStore->get('aws_backup_secret'),
            ],
            'region' => 'eu-central-1',
            'version' => 'latest',
        ]), 'kidup-backups');
    }

    public function backup(){
        foreach ($this->folders as $f) {
            $folder = $this->compressFolder($f);

            // upload to S3
            $c = new Config();
            $this->s3->write('files/'.$folder, $this->local->read($folder), $c);

            // remove locally
            $this->local->delete($folder);
        }
    }

    public function compressFolder($folder){
        $backupName = str_replace('/', '_', $folder).'_'.time().'.tar.gz';
        $cmd = 'tar -czf '.$backupName.' '.$folder;
        $process = new \Symfony\Component\Process\Process($cmd);
        $process->run();

        return $backupName;
    }

}