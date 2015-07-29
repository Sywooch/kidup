<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "profile".
 *
 * @property integer $user_id
 * @property string $description
 * @property string $first_name
 * @property string $last_name
 * @property string $img
 * @property string $phone_country
 * @property string $phone_number
 * @property integer $email_verified
 * @property integer $phone_verified
 * @property integer $identity_verified
 * @property integer $location_verified
 * @property string $language
 * @property integer $currency_id
 * @property integer $birthday
 * @property integer $nationality
 *
 * @property \app\models\base\Country $nationality0
 * @property \app\models\base\User $user
 * @property \app\models\base\Currency $currency
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'first_name', 'last_name'], 'required'],
            [['user_id', 'email_verified', 'phone_verified', 'identity_verified', 'location_verified', 'currency_id', 'birthday', 'nationality'], 'integer'],
            [['description'], 'string'],
            [['first_name'], 'string', 'max' => 128],
            [['last_name', 'img'], 'string', 'max' => 256],
            [['phone_country'], 'string', 'max' => 5],
            [['phone_number'], 'string', 'max' => 50],
            [['language'], 'string', 'max' => 6]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('app', 'User ID'),
            'description' => Yii::t('app', 'Description'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'img' => Yii::t('app', 'Img'),
            'phone_country' => Yii::t('app', 'Phone Country'),
            'phone_number' => Yii::t('app', 'Phone Number'),
            'email_verified' => Yii::t('app', 'Email Verified'),
            'phone_verified' => Yii::t('app', 'Phone Verified'),
            'identity_verified' => Yii::t('app', 'Identity Verified'),
            'location_verified' => Yii::t('app', 'Location Verified'),
            'language' => Yii::t('app', 'Language'),
            'currency_id' => Yii::t('app', 'Currency ID'),
            'birthday' => Yii::t('app', 'Birthday'),
            'nationality' => Yii::t('app', 'Nationality'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNationality0()
    {
        return $this->hasOne(\app\models\base\Country::className(), ['id' => 'nationality']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\app\models\base\User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(\app\models\base\Currency::className(), ['id' => 'currency_id']);
    }
}
