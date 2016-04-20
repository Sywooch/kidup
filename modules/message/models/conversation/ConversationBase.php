<?php

namespace message\models\conversation;

use booking\models\booking\Booking;
use notification\models\MailAccount;
use user\models\profile\Profile;
use user\models\user\User;
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
 * @property \user\models\user\User $initiaterUser
 * @property \user\models\user\User $targetUser
 * @property MailAccount[] $mailAccounts
 * @property \message\models\message\Message[] $messages
 * @property \message\models\message\Message $lastMessage
 * @property User $otherUser
 */
class ConversationBase extends \app\components\models\BaseActiveRecord
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
    public function rules()
    {
        return [
            [['initiater_user_id', 'target_user_id', 'title', 'created_at'], 'required'],
            [['initiater_user_id', 'target_user_id', 'created_at', 'updated_at', 'booking_id'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [
                ['target_user_id'],
                'exist',
                'targetClass' => User::className(),
                'targetAttribute' => ['target_user_id' => 'id']
            ],
            [
                ['initiater_user_id'],
                'exist',
                'targetClass' => User::className(),
                'targetAttribute' => ['initiater_user_id' => 'id']
            ],
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
        return $this->hasMany(\message\models\message\Message::className(), ['conversation_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastMessage()
    {
        return $this->hasOne(\message\models\message\Message::className(), ['conversation_id' => 'id'])->orderBy('message.created_at DESC');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBooking()
    {
        return $this->hasOne(Booking::className(), ['id' => 'booking_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOtherUser()
    {
        if (Yii::$app->user->id == $this->target_user_id) {
            $target = "initiater_user_id";
        } else {
            $target = "target_user_id";
        }
        return $this->hasOne(User::className(), ['id' => $target]);
    }

}
