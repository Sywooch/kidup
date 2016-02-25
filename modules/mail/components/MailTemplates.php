<?php
namespace mail\components;

use Yii;

class MailTemplates
{

    public static $mails = [
        'booking_owner_confirmation' => ['user'],
        'example' => ['user', 'bla']
    ];

    public static function loadAliases() {
        Yii::setAlias('@mail', '@app/modules/mail');
        Yii::setAlias('@mail-layouts', '@mail/views/layouts/');
        Yii::setAlias('@mail-assets', '@mail/assets/');
    }

}