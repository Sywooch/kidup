<?php

namespace app\backup;

/**
 * Responsible for backing up the database
 * Class Database
 * @package app\backup
 */
interface BackupInterface{
    public function backup();
}