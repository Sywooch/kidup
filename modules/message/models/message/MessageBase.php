<?php

namespace message\models\message;

use Yii;
use yii\db\ActiveRecord;

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
 * @property \notification\models\MailMessage[] $mailMessages
 * @property \user\models\user\User $senderUser
 * @property \user\models\user\User $receiverUser
 * @property \message\models\conversation\Conversation $conversation
 */
class MessageBase extends \app\components\models\BaseActiveRecord
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
            'id' => 'ID',
            'conversation_id' => 'Conversation ID',
            'message' => 'Message',
            'sender_user_id' => 'Sender User ID',
            'read_by_receiver' => 'Read By Receiver',
            'receiver_user_id' => 'Receiver User ID',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    public static function find(){
        return new MessageQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailMessages()
    {
        return $this->hasMany(\notification\models\MailMessage::className(), ['message_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSenderUser()
    {
        return $this->hasOne(\user\models\user\User::className(), ['id' => 'sender_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiverUser()
    {
        return $this->hasOne(\user\models\user\User::className(), ['id' => 'receiver_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversation()
    {
        return $this->hasOne(\message\models\conversation\Conversation::className(), ['id' => 'conversation_id']);
    }
}
