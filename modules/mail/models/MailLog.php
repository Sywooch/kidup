<?php
namespace mail\models;

use Yii;
use yii\helpers\Json;

/**
 * Login form
 */
class MailLog extends base\MailLog
{
    public static function create($type, $email, $data, $id)
    {
        $log = new MailLog();
        $log->id = $id;
        $log->type = $type;
        $log->email = $email;
        $log->data = $data;
        $log->created_at = time();
        $log->save();
        return $log;
    }

    public static function getUniqueId()
    {
        return \Yii::$app->security->generateRandomString(64);
    }
}