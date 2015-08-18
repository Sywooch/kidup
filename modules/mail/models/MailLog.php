<?php
namespace app\modules\mail\models;

use Yii;
use yii\helpers\Json;

/**
 * Login form
 */
class MailLog extends \app\models\base\MailLog
{
    public static function create($type, $email, $data, $id)
    {
        $log = new MailLog();
        $log->id = $id;
        $log->type = $type;
        $log->email = $email;
        $log->data = Json::encode($data);
        $log->created_at = time();
        $log->save();
        return $log;
    }

    public static function getUniqueId()
    {
        return \Yii::$app->security->generateRandomString(64);
    }
}