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
 * @property integer $location_id
 *
 * @property \app\models\base\Location $location
 * @property \app\models\base\Country $nationality0
 * @property \app\models\base\Currency $currency
 * @property \app\models\base\User $user
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
            [['user_id', 'first_name', 'last_name', 'email_verified', 'phone_verified', 'identity_verified', 'location_verified'], 'required'],
            [['user_id', 'email_verified', 'phone_verified', 'identity_verified', 'location_verified', 'currency_id', 'birthday', 'nationality', 'location_id'], 'integer'],
            [['description'], 'string'],
            [['first_name'], 'string', 'max' => 128],
            [['last_name', 'img'], 'string', 'max' => 256],
            [['phone_country'], 'string', 'max' => 5],
            [['phone_number'], 'string', 'max' => 50],
            [['language'], 'string', 'max' => 6],
            [['location_id'], 'exist', 'skipOnError' => true, 'targetClass' => Location::className(), 'targetAttribute' => ['location_id' => 'id']],
            [['nationality'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['nationality' => 'id']],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['currency_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']]
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
            'location_id' => Yii::t('app', 'Location ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(\app\models\base\Location::className(), ['id' => 'location_id']);
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
    public function getCurrency()
    {
        return $this->hasOne(\app\models\base\Currency::className(), ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\app\models\base\User::className(), ['id' => 'user_id']);
    }




}
