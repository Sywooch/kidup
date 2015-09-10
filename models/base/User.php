<?php

namespace app\models\base;

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
 * @property \app\models\Booking[] $bookings
 * @property \app\models\Child[] $children
 * @property \app\models\Conversation[] $conversations
 * @property \app\models\Conversation[] $conversations0
 * @property \app\models\Item[] $items
 * @property \app\models\Location[] $locations
 * @property \app\models\MailAccount[] $mailAccounts
 * @property \app\models\Media[] $media
 * @property \app\models\Message[] $messages
 * @property \app\models\Message[] $messages0
 * @property \app\models\Payin[] $payins
 * @property \app\models\Payout[] $payouts
 * @property \app\models\PayoutMethod[] $payoutMethods
 * @property \app\models\Profile $profile
 * @property \app\models\Review[] $reviews
 * @property \app\models\Review[] $reviews0
 * @property \app\models\Setting[] $settings
 * @property \app\models\Token[] $tokens
 * @property \app\models\UserHasPromotionCode[] $userHasPromotionCodes
 * @property \app\models\PromotionCode[] $promotionCodes
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
            [['email', 'password_hash', 'auth_key', 'flags', 'status', 'role', 'created_at', 'updated_at'], 'required'],
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
        return $this->hasMany(\app\models\Booking::className(), ['renter_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(\app\models\Child::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversations()
    {
        return $this->hasMany(\app\models\Conversation::className(), ['target_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversations0()
    {
        return $this->hasMany(\app\models\Conversation::className(), ['initiater_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(\app\models\Item::className(), ['owner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocations()
    {
        return $this->hasMany(\app\models\Location::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailAccounts()
    {
        return $this->hasMany(\app\models\MailAccount::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasMany(\app\models\Media::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(\app\models\Message::className(), ['sender_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages0()
    {
        return $this->hasMany(\app\models\Message::className(), ['receiver_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayins()
    {
        return $this->hasMany(\app\models\Payin::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayouts()
    {
        return $this->hasMany(\app\models\Payout::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayoutMethods()
    {
        return $this->hasMany(\app\models\PayoutMethod::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(\app\models\Profile::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(\app\models\Review::className(), ['reviewed_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviews0()
    {
        return $this->hasMany(\app\models\Review::className(), ['reviewer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSettings()
    {
        return $this->hasMany(\app\models\Setting::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTokens()
    {
        return $this->hasMany(\app\models\Token::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserHasPromotionCodes()
    {
        return $this->hasMany(\app\models\UserHasPromotionCode::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromotionCodes()
    {
        return $this->hasMany(\app\models\PromotionCode::className(), ['id' => 'promotion_code_id'])->viaTable('user_has_promotion_code', ['user_id' => 'id']);
    }




}
