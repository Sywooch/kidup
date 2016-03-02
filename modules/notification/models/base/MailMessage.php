<?php

namespace notification\models\base;

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
 * @property \mail\models\MailAccount $mailAccountName
 * @property \message\models\Message $message0
 */
class MailMessage extends \app\models\BaseActiveRecord
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
            [['subject'], 'string', 'max' => 512]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'message' => 'Message',
            'from_email' => 'From Email',
            'message_id' => 'Message ID',
            'subject' => 'Subject',
            'created_at' => 'Created At',
            'mail_account_name' => 'Mail Account Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailAccountName()
    {
        return $this->hasOne(\mail\models\MailAccount::className(), ['name' => 'mail_account_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessage0()
    {
        return $this->hasOne(\message\models\Message::className(), ['id' => 'message_id']);
    }


}
