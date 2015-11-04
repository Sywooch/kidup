<?php

namespace user\models\base;

use \api\models\oauth\OauthAccessToken;
use Yii;

/**
 * This is the base-model class for table "user".
 *
 * @property integer $id
 * @property string $email
 * @property string $password_hash
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
 * @property \booking\models\Booking[] $bookings
 * @property \user\models\Child[] $children
 * @property \message\models\Conversation[] $conversations
 * @property \message\models\Conversation[] $conversations0
 * @property \item\models\Item[] $items
 * @property \item\models\Location[] $locations
 * @property \mail\models\MailAccount[] $mailAccounts
 * @property \item\models\Media[] $media
 * @property \message\models\Message[] $messages
 * @property \message\models\Message[] $messages0
 * @property \booking\models\Payin[] $payins
 * @property \booking\models\Payout[] $payouts
 * @property \user\models\Profile $profile
 * @property \review\models\Review[] $reviews
 * @property \review\models\Review[] $reviews0
 * @property \user\models\Setting[] $settings
 * @property \user\models\SocialAccount[] $socialAccounts
 * @property \user\models\base\Token[] $tokens
 * @property \api\models\oauth\OauthAccessToken[] $validOauthAccessTokens
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
            [['email', 'password_hash', 'status', 'role', 'created_at', 'updated_at'], 'required'],
            [['confirmed_at', 'blocked_at', 'flags', 'status', 'role', 'created_at', 'updated_at'], 'integer'],
            [['email', 'unconfirmed_email'], 'string', 'max' => 255],
            [['password_hash'], 'string', 'max' => 60],
            [['registration_ip'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'password_hash' => 'Password Hash',
            'confirmed_at' => 'Confirmed At',
            'unconfirmed_email' => 'Unconfirmed Email',
            'blocked_at' => 'Blocked At',
            'registration_ip' => 'Registration Ip',
            'flags' => 'Flags',
            'status' => 'Status',
            'role' => 'Role',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(\booking\models\Booking::className(), ['renter_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversations()
    {
        return $this->hasMany(\message\models\Conversation::className(), ['initiater_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversations0()
    {
        return $this->hasMany(\message\models\Conversation::className(), ['target_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(\item\models\Item::className(), ['owner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocations()
    {
        return $this->hasMany(\item\models\Location::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailAccounts()
    {
        return $this->hasMany(\mail\models\MailAccount::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasMany(\item\models\Media::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(\message\models\Message::className(), ['sender_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages0()
    {
        return $this->hasMany(\message\models\Message::className(), ['receiver_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayins()
    {
        return $this->hasMany(\booking\models\Payin::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayouts()
    {
        return $this->hasMany(\booking\models\Payout::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(\user\models\Profile::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(\review\models\Review::className(), ['reviewer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviews0()
    {
        return $this->hasMany(\review\models\Review::className(), ['reviewed_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSettings()
    {
        return $this->hasMany(\user\models\Setting::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSocialAccounts()
    {
        return $this->hasMany(\user\models\SocialAccount::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTokens()
    {
        return $this->hasMany(\user\models\base\Token::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValidOauthAccessTokens()
    {
        return $this->hasMany(OauthAccessToken::className(), ['user_id' => 'id'])->andWhere('expires >= :t')->params([':t' => time()]);
    }
}
