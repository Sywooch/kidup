<?php

namespace notification\models;

use Carbon\Carbon;
use user\models\user\User;
use Yii;

/**
 * This is the base-model class for table "notification_push_log".
 *
 * @property integer $id
 * @property string  $template
 * @property integer $receiver_id
 * @property string  $receiver_platform
 * @property string  $receiver_arn_endpoint
 * @property string  $variables
 * @property string  $view
 * @property string  $hash
 * @property integer $created_at
 */
class NotificationPushLog extends \app\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification_push_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['receiver_id', 'template'], 'required'],
            [['receiver_platform', 'receiver_arn_endpoint', 'variables', 'view', 'template', 'hash'], 'string'],
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
            'id' => Yii::t('notification.push_log.id', 'ID'),
            'hash' => Yii::t('notification.push_log.hash', 'Hash'),
            'template' => Yii::t('notification.push_log.template', 'Template'),
            'receiver_id' => Yii::t('notification.push_log.receiver_id', 'Receiver ID'),
            'receiver_platform' => Yii::t('notification.push_log.receiver_platform', 'Receiver platform'),
            'receiver_arn_endpoint' => Yii::t('notification.push_log.receiver_arn_endpoint', 'ARN endpoint'),
            'variables' => Yii::t('notification.push_log.variables', 'Variables'),
            'view' => Yii::t('notification.push_log.view', 'View'),
            'created_at' => Yii::t('notification.push_log.created_at', 'Created at'),
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
