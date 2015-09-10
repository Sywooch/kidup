<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "mail_message".
 *
 * @property integer $id
 * @property string $message
 * @property string $from_email
 * @property integer $message_id
 * @property string $subject
 * @property integer $created_at
 * @property string $mail_account_name
 *
 * @property \app\models\base\Message $message0
 * @property \app\models\base\MailAccount $mailAccountName
 */
class MailMessage extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mail_message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message', 'from_email', 'message_id', 'mail_account_name'], 'required'],
            [['message'], 'string'],
            [['message_id', 'created_at'], 'integer'],
            [['from_email', 'mail_account_name'], 'string', 'max' => 128],
            [['subject'], 'string', 'max' => 512],
            [['message_id'], 'exist', 'skipOnError' => true, 'targetClass' => Message::className(), 'targetAttribute' => ['message_id' => 'id']],
            [['mail_account_name'], 'exist', 'skipOnError' => true, 'targetClass' => MailAccount::className(), 'targetAttribute' => ['mail_account_name' => 'name']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'message' => Yii::t('app', 'Message'),
            'from_email' => Yii::t('app', 'From Email'),
            'message_id' => Yii::t('app', 'Message ID'),
            'subject' => Yii::t('app', 'Subject'),
            'created_at' => Yii::t('app', 'Created At'),
            'mail_account_name' => Yii::t('app', 'Mail Account Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessage0()
    {
        return $this->hasOne(\app\models\base\Message::className(), ['id' => 'message_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailAccountName()
    {
        return $this->hasOne(\app\models\base\MailAccount::className(), ['name' => 'mail_account_name']);
    }




}
