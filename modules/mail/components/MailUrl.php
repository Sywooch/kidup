<?php
namespace mail\components;

use yii\base\Model;
use yii\helpers\Url;

class MailUrl extends Model
{

    /**
     * Create a proxy URL to a link.
     *
     * @param $link URL.
     * @param $mailId Mail identifier.
     * @return string Proxy URL.
     */
    public static function to($link, $mailId)
    {
        return Url::to('@web/mail/click?url=' . base64_encode($link) . '&mailId=' . $mailId, true);
    }

}