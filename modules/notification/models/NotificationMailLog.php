<?php

namespace notification\models;

use Carbon\Carbon;
use user\models\User;
use Yii;

/**
 * This is the base-model class for table "notification_mail_log".
 *
 * @property integer $id
 * @property string  $template
 * @property integer $receiver_id
 * @property string  $subject
 * @property string  $to
 * @property string  $reply_to
 * @property string  $from
 * @property string  $variables
 * @property string  $view
 * @property string  $hash
 * @property integer $created_at
 */
class NotificationMailLog extends \app\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification_mail_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['receiver_id', 'template'], 'required'],
            [['template', 'subject', 'to', 'reply_to', 'from', 'variables', 'view', 'hash'], 'string'],
            [['receiver_id', 'created_at'], 'integer'],
            [
                ['receiver_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['receiver_id' => 'id']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('notification.mail_log.id', 'ID'),
            'template' => Yii::t('notification.mail_log.template', 'Template'),
            'hash' => Yii::t('notification.mail_log.hash', 'Hash'),
            'receiver_id' => Yii::t('notification.mail_log.receiver_id', 'Receiver ID'),
            'subject' => Yii::t('notification.mail_log.subject', 'Subject'),
            'to' => Yii::t('notification.mail_log.to', 'To'),
            'reply_to' => Yii::t('notification.mail_log.reply_to', 'Reply to'),
            'from' => Yii::t('notification.mail_log.from', 'From'),
            'variables' => Yii::t('notification.mail_log.variables', 'Variables'),
            'view' => Yii::t('notification.mail_log.view', 'View'),
            'created_at' => Yii::t('notification.mail_log.created_at', 'Created at'),
        ];
    }

    public function beforeSave($insert)
    {
        if ($insert == true) {
            $this->created_at = Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp;
            $this->hash = md5(uniqid() . microtime(true)) . uniqid();
        }

        return parent::beforeSave($insert);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiver()
    {
        return $this->hasOne(User::className(), ['id' => 'receiver_id']);
    }

}
