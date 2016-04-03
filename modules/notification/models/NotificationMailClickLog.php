<?php

namespace notification\models;

use Carbon\Carbon;
use user\models\user\User;
use Yii;

/**
 * This is the base-model class for table "notification_mail_click_log".
 *
 * @property integer $mail_id
 * @property string  $link_text
 * @property string  $url
 * @property integer $created_at
 */
class NotificationMailClickLog extends \app\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification_mail_click_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mail_id', 'link_text', 'url'], 'required'],
            [['link_text', 'url'], 'string'],
            [['mail_id', 'created_at'], 'integer'],
            [
                ['mail_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => NotificationMailLog::className(),
                'targetAttribute' => ['mail_id' => 'id']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('notification.mail_click_log.id', 'ID'),
            'link_text' => Yii::t('notification.mail_click_log.link_text', 'Link text'),
            'url' => Yii::t('notification.mail_click_log.url', 'URL'),
            'created_at' => Yii::t('notification.mail_click_log.created_at', 'Created at'),
        ];
    }

    public function beforeSave($insert)
    {
        if ($insert == true) {
            $this->created_at = Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp;
        }

        return parent::beforeSave($insert);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMail()
    {
        return $this->hasOne(NotificationMailLog::className(), ['id' => 'mail_id']);
    }
    
}
