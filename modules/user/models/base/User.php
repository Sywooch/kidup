<?php

namespace user\models\base;

use Yii;

/**
 * This is the base-model class for table "user".
 *
 * @property integer $id
 * @property string $email
 * @property string $password_hash
 * @property string $auth_key
 * @property integer $confirmed_at
 * @property string $unconfirmed_email
 * @property integer $blocked_at
 * @property string $registration_ip
 * @property integer $flags
 * @property integer $status
 * @property integer $role
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property \booking\models\base\Booking[] $bookings
 * @property \user\models\base\Child[] $children
 * @property \message\models\base\Conversation[] $conversations
 * @property \message\models\base\Conversation[] $conversations0
 * @property \item\models\base\Item[] $items
 * @property \item\models\base\Location[] $locations
 * @property \item\models\base\Log[] $logs
 * @property \mail\models\base\MailAccount[] $mailAccounts
 * @property \item\models\base\Media[] $media
 * @property \message\models\base\Message[] $messages
 * @property \message\models\base\Message[] $messages0
 * @property \booking\models\base\Payin[] $payins
 * @property \booking\models\base\Payout[] $payouts
 * @property \user\models\base\Profile $profile
 * @property \review\models\base\Review[] $reviews
 * @property \review\models\base\Review[] $reviews0
 * @property \user\models\base\Setting[] $settings
 * @property \user\models\base\SocialAccount[] $socialAccounts
 * @property \user\models\base\Token[] $tokens
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password_hash', 'auth_key', 'status', 'role', 'created_at', 'updated_at'], 'required'],
            [['confirmed_at', 'blocked_at', 'flags', 'status', 'role', 'created_at', 'updated_at'], 'integer'],
            [['email', 'unconfirmed_email'], 'string', 'max' => 255],
            [['password_hash'], 'string', 'max' => 60],
            [['auth_key'], 'string', 'max' => 32],
            [['registration_ip'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'email' => Yii::t('app', 'Email'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'confirmed_at' => Yii::t('app', 'Confirmed At'),
            'unconfirmed_email' => Yii::t('app', 'Unconfirmed Email'),
            'blocked_at' => Yii::t('app', 'Blocked At'),
            'registration_ip' => Yii::t('app', 'Registration Ip'),
            'flags' => Yii::t('app', 'Flags'),
            'status' => Yii::t('app', 'Status'),
            'role' => Yii::t('app', 'Role'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(\booking\models\base\Booking::className(), ['renter_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(\user\models\base\Child::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversations()
    {
        return $this->hasMany(\message\models\base\Conversation::className(), ['initiater_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversations0()
    {
        return $this->hasMany(\message\models\base\Conversation::className(), ['target_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(\item\models\base\Item::className(), ['owner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocations()
    {
        return $this->hasMany(\item\models\base\Location::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogs()
    {
        return $this->hasMany(\item\models\base\Log::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailAccounts()
    {
        return $this->hasMany(\mail\models\base\MailAccount::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasMany(\item\models\base\Media::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(\message\models\base\Message::className(), ['sender_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages0()
    {
        return $this->hasMany(\message\models\base\Message::className(), ['receiver_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayins()
    {
        return $this->hasMany(\booking\models\base\Payin::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayouts()
    {
        return $this->hasMany(\booking\models\base\Payout::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(\user\models\base\Profile::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(\review\models\base\Review::className(), ['reviewer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviews0()
    {
        return $this->hasMany(\review\models\base\Review::className(), ['reviewed_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSettings()
    {
        return $this->hasMany(\user\models\base\Setting::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSocialAccounts()
    {
        return $this->hasMany(\user\models\base\SocialAccount::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTokens()
    {
        return $this->hasMany(\user\models\base\Token::className(), ['user_id' => 'id']);
    }
}
