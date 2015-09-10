<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "message".
 *
 * @property integer $id
 * @property integer $conversation_id
 * @property string $message
 * @property integer $sender_user_id
 * @property integer $read_by_receiver
 * @property integer $receiver_user_id
 * @property integer $updated_at
 * @property integer $created_at
 *
 * @property \app\models\base\MailMessage[] $mailMessages
 * @property \app\models\base\User $senderUser
 * @property \app\models\base\User $receiverUser
 * @property \app\models\base\Conversation $conversation
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['conversation_id', 'message', 'sender_user_id', 'updated_at', 'created_at'], 'required'],
            [['conversation_id', 'sender_user_id', 'read_by_receiver', 'receiver_user_id', 'updated_at', 'created_at'], 'integer'],
            [['message'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'conversation_id' => Yii::t('app', 'Conversation ID'),
            'message' => Yii::t('app', 'Message'),
            'sender_user_id' => Yii::t('app', 'Sender User ID'),
            'read_by_receiver' => Yii::t('app', 'Read By Receiver'),
            'receiver_user_id' => Yii::t('app', 'Receiver User ID'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailMessages()
    {
        return $this->hasMany(\app\models\base\MailMessage::className(), ['message_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSenderUser()
    {
        return $this->hasOne(\app\models\base\User::className(), ['id' => 'sender_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiverUser()
    {
        return $this->hasOne(\app\models\base\User::className(), ['id' => 'receiver_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversation()
    {
        return $this->hasOne(\app\models\base\Conversation::className(), ['id' => 'conversation_id']);
    }
}
