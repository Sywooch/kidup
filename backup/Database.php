<?php

namespace app\backup;

use BackupManager\Compressors;
use BackupManager\Config\Config;
use BackupManager\Databases;
use BackupManager\Filesystems;
use BackupManager\Manager;

/**
 * Responsible for backing up the database
 * Class Database
 * @package app\backup
 */
class Database implements BackupInterface{

    private $manager;

    public function __construct(){
        $filesystems = new Filesystems\FilesystemProvider(Config::fromPhpFile(\Yii::$aliases['@app'].'/config/backup/storage.php'));
        $filesystems->add(new \app\backup\Awss3Filesystem);
        $filesystems->add(new Filesystems\LocalFilesystem);
        $databases = new Databases\DatabaseProvider(Config::fromPhpFile(\Yii::$aliases['@app'].'/config/backup/database.php'));
        $databases->add(new Databases\MysqlDatabase);
        $compressors = new Compressors\CompressorProvider;
        $compressors->add(new Compressors\GzipCompressor);

        $this->manager = new Manager($filesystems, $databases, $compressors);
    }

    public function backup(){
        $this->manager->makeBackup()->run('beta', 's3', 'database/'.time().'.sql', 'gzip');
    }

}