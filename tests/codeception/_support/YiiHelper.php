<?php

namespace codecept\_support;
use FunctionalTester;
use Yii;

class YiiHelper
{

    /**
     * Get the mail folder.
     *
     * @return string absolute path to the mail folder.
     */
    public static function getMailFolder() {
        return Yii::$aliases['@runtime'] . '/mail';
    }

    /**
     * List all e-mails in the queue.
     *
     * @return array list of files
     */
    public static function listEmails() {
        return scandir(self::getMailFolder());
    }

}
