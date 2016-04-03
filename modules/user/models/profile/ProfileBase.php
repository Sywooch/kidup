<?php

namespace user\models\profile;

use app\models\BaseActiveRecord;
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
 * @property \item\models\location\Location $location
 * @property \user\models\country\Country $nationality0
 * @property \user\models\currency\Currency $currency
 * @property \user\models\user\User $user
 */
class ProfileBase extends BaseActiveRecord
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
            [
                [
                    'user_id',
                    'email_verified',
                    'phone_verified',
                    'identity_verified',
                    'location_verified',
                    'currency_id',
                    'nationality',
                    'phone_country',
                    'phone_number'
                ],
                'integer'
            ],
            [['description'], 'string'],
            [['first_name'], 'string', 'max' => 128],
            [['last_name', 'img'], 'string', 'max' => 256],
            [['phone_country'], 'string', 'max' => 5],
            [['phone_number'], 'string', 'max' => 50],
            [['language'], 'string', 'max' => 6],
            [['img'], 'safe'],
            [['img'], 'file', 'extensions' => 'jpg, gif, png'],
            [['birthday'], 'date', 'format' => 'dd-mm-yyyy'],
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
        return $this->hasOne(\item\models\location\Location::className(), ['id' => 'location_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNationality0()
    {
        return $this->hasOne(\user\models\country\Country::className(), ['id' => 'nationality']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(\user\models\currency\Currency::className(), ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\user\models\user\User::className(), ['id' => 'user_id']);
    }


}
