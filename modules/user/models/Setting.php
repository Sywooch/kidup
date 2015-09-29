<?php

namespace user\models;

use Carbon\Carbon;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Setting extends \app\models\base\Setting
{
    const MAIL_BOOKING_REMINDER = 'rent_reminder';
    const MESSAGE_UPDATE = 'message_update';
    const BOOKING_STATUS_CHANGE = 'rent_status_change';
    const NEWSLETTER = 'newsletter';

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

    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['type'], 'string', 'max' => 50],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => function () {
                    return Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp;
                }
            ],
        ];
    }
}
