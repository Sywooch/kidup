<?php

namespace user\models\base;

use item\models\Location;
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
 * @property \item\models\Location $location
 * @property \user\models\Country $nationality0
 * @property \user\models\base\Currency $currency
 * @property \user\models\User $user
 */
class Profile extends \app\models\BaseActiveRecord
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
            [
                [
                    'user_id',
                    'first_name',
                    'last_name',
                    'email_verified',
                    'phone_verified',
                    'identity_verified',
                    'location_verified'
                ],
                'required'
            ],
            [
                [
                    'user_id',
                    'email_verified',
                    'phone_verified',
                    'identity_verified',
                    'location_verified',
                    'currency_id',
                    'birthday',
                    'nationality',
                    'location_id'
                ],
                'integer'
            ],
            [['description'], 'string'],
            [['first_name'], 'string', 'max' => 128],
            [['last_name', 'img'], 'string', 'max' => 256],
            [['phone_country'], 'string', 'max' => 5],
            [['phone_number'], 'string', 'max' => 50],
            [['language'], 'string', 'max' => 6],
            [
                ['location_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Location::className(),
                'targetAttribute' => ['location_id' => 'id']
            ],
            [
                ['nationality'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Country::className(),
                'targetAttribute' => ['nationality' => 'id']
            ],
            [
                ['currency_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Currency::className(),
                'targetAttribute' => ['currency_id' => 'id']
            ],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['user_id' => 'id']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('profile.attributes.user_id', 'User'),
            'description' => Yii::t('profile.attributes.description', 'Description'),
            'first_name' => Yii::t('profile.attributes.first_name', 'First Name'),
            'last_name' => Yii::t('profile.attributes.last_name', 'Last Name'),
            'img' => Yii::t('profile.attributes.profile_image', 'Profile Image'),
            'phone_country' => Yii::t('profile.attributes.phone_country', 'Phone Country'),
            'phone_number' => Yii::t('profile.attributes.phone_number', 'Phone Number'),
            'email_verified' => Yii::t('profile.attributes.email_is_verified', 'Email Verified'),
            'phone_verified' => Yii::t('profile.attributes.phone_is_verfified', 'Phone Verified'),
            'identity_verified' => Yii::t('profile.attributes.identifiy_is_verified', 'Identity Verified'),
            'location_verified' => Yii::t('profile.attributes.location_is_verified', 'Location Verified'),
            'language' => Yii::t('profile.attributes.language', 'Language'),
            'currency_id' => Yii::t('profile.attributes.currency_id', 'Currency'),
            'birthday' => Yii::t('profile.attributes.birthday', 'Birthday'),
            'nationality' => Yii::t('profile.attributes.nationality', 'Nationality'),
            'location_id' => Yii::t('profile.attributes.location_id', 'Location'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(\item\models\Location::className(), ['id' => 'location_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNationality0()
    {
        return $this->hasOne(\user\models\Country::className(), ['id' => 'nationality']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(\user\models\base\Currency::className(), ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\user\models\User::className(), ['id' => 'user_id']);
    }


}
