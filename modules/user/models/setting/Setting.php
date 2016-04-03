<?php

namespace user\models\setting;

use Yii;

class Setting extends SettingBase
{
    const MAIL_BOOKING_REMINDER = 'rent_reminder';
    const MESSAGE_UPDATE = 'message_update';
    const BOOKING_STATUS_CHANGE = 'rent_status_change';
    const NEWSLETTER = 'newsletter';

    /**
     * Returns an array of email setting strings
     * @return array
     */
    public static function getEmailSettings()
    {
        return [
            Setting::MAIL_BOOKING_REMINDER => \Yii::t('user.settings.mail.reservation_start',
                'Am about to start a reservation.'),
            Setting::MESSAGE_UPDATE => \Yii::t('user.settings.mail.receive_message_from_kidup_user',
                'I receive a message from another person on KidUp.'),
            Setting::BOOKING_STATUS_CHANGE => \Yii::t('user.settings.mail.reservation_status_changes',
                'My outstanding booking request is accepted or declined.'),
            Setting::NEWSLETTER => \Yii::t('user.settings.mail.kidup_newsletter_promo',
                'Kidup wants to share some exciting news or updates.'),
        ];
    }
}
