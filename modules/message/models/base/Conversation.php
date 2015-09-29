<?php

namespace message\models\base;

use mail\models\base\MailAccount;
use message\models\base\Message;
use user\models\base\User;
use Yii;

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
 * @property \user\models\base\User $initiaterUser
 * @property \user\models\base\User $targetUser
 * @property MailAccount[] $mailAccounts
 * @property Message[] $messages
 */
class Conversation extends \yii\db\ActiveRecord
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
            [['title'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'initiater_user_id' => Yii::t('app', 'Initiater User ID'),
            'target_user_id' => Yii::t('app', 'Target User ID'),
            'title' => Yii::t('app', 'Title'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'booking_id' => Yii::t('app', 'Booking ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInitiaterUser()
    {
        return $this->hasOne(\user\models\base\User::className(), ['id' => 'initiater_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTargetUser()
    {
        return $this->hasOne(\user\models\base\User::className(), ['id' => 'target_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailAccounts()
    {
        return $this->hasMany(\mail\models\base\MailAccount::className(), ['conversation_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(\message\models\base\Message::className(), ['conversation_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBooking()
    {
        return $this->hasMany(\message\models\base\Message::className(), ['conversation_id' => 'id']);
    }
}
