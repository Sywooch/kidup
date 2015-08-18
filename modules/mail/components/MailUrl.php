<?php
namespace app\modules\mail\components;

use yii\base\Model;
use yii\helpers\Url;

class MailUrl extends Model
{
    public static function to($link, $mailId)
    {
        return Url::to('@web/mail/click?url=' . base64_encode($link) . '&mailId=' . $mailId, true);
    }
}