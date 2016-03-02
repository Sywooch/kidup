<?php

namespace item\models\base;

use Yii;

/**
 * This is the base-model class for table "location".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $country
 * @property string $city
 * @property string $zip_code
 * @property string $street_name
 * @property string $street_number
 * @property string $street_suffix
 * @property double $longitude
 * @property double $latitude
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property \item\models\base\Item[] $items
 * @property \user\models\base\Country $country0
 * @property \user\models\base\User $user
 */
class Location extends \app\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'location';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'country', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['longitude', 'latitude', 'user_id', 'created_at', 'updated_at'], 'required'],
            [['longitude', 'latitude'], 'number'],
            [['city'], 'string', 'max' => 100],
            [['zip_code', 'street_suffix'], 'string', 'max' => 50],
            [['street_name'], 'string', 'max' => 256],
            [['street_number'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('location.attributes.id', 'ID'),
            'type' => Yii::t('location.attributes.type', 'Type'),
            'country' => Yii::t('location.attributes.country', 'Country'),
            'city' => Yii::t('location.attributes.city', 'City'),
            'zip_code' => Yii::t('location.attributes.zip_code', 'Zip Code'),
            'street_name' => Yii::t('location.attributes.street_name', 'Street Name'),
            'street_number' => Yii::t('location.attributes.street_number', 'Street Number'),
            'street_suffix' => Yii::t('location.attributes.street_suffix', 'Street Suffix'),
            'longitude' => Yii::t('location.attributes.longitude', 'Longitude'),
            'latitude' => Yii::t('location.attributes.latitude', 'Latitude'),
            'user_id' => Yii::t('location.attributes.user_id', 'User'),
            'created_at' => Yii::t('location.attributes.created_at', 'Created At'),
            'updated_at' => Yii::t('location.attributes.updaetd_at', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(\item\models\base\Item::className(), ['location_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry0()
    {
        return $this->hasOne(\user\models\base\Country::className(), ['id' => 'country']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\user\models\base\User::className(), ['id' => 'user_id']);
    }
}
