<?php

namespace message\models\base;

use booking\models\Booking;
use notifications\models\MailAccount;
use user\models\User;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the base-model class for table "conversation".
 *
 * @property integer $id
 * @property integer $initiater_user_id
 * @property integer $target_user_id
 * @property string $title
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $booking_id
 *
 * @property \user\models\User $initiaterUser
 * @property \user\models\User $targetUser
 * @property MailAccount[] $mailAccounts
 * @property \message\models\Message[] $messages
 * @property \message\models\Message $lastMessage
 */
class Conversation extends \app\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'conversation';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_INIT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['initiater_user_id', 'target_user_id', 'title', 'created_at'], 'required'],
            [['initiater_user_id', 'target_user_id', 'created_at', 'updated_at', 'booking_id'], 'integer'],
            [['title'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'initiater_user_id' => 'Initiater User ID',
            'target_user_id' => 'Target User ID',
            'title' => 'Title',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'booking_id' => 'Booking ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInitiaterUser()
    {
        return $this->hasOne(User::className(), ['id' => 'initiater_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTargetUser()
    {
        return $this->hasOne(User::className(), ['id' => 'target_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailAccounts()
    {
        return $this->hasMany(MailAccount::className(), ['conversation_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(\message\models\Message::className(), ['conversation_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastMessage()
    {
        return $this->hasOne(\message\models\Message::className(), ['conversation_id' => 'id'])->orderBy('message.created_at DESC');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBooking()
    {
        return $this->hasOne(Booking::className(), ['id' => 'booking_id']);
    }
}
