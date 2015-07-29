<?php

namespace app\models\base;

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
 * @property double $longitude
 * @property double $latitude
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property \app\models\base\Item[] $items
 * @property \app\models\base\Country $country0
 * @property \app\models\base\User $user
 */
class Location extends \yii\db\ActiveRecord
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
            [['zip_code'], 'string', 'max' => 50],
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
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Type'),
            'country' => Yii::t('app', 'Country'),
            'city' => Yii::t('app', 'City'),
            'zip_code' => Yii::t('app', 'Zip Code'),
            'street_name' => Yii::t('app', 'Street Name'),
            'street_number' => Yii::t('app', 'Street Number'),
            'longitude' => Yii::t('app', 'Longitude'),
            'latitude' => Yii::t('app', 'Latitude'),
            'user_id' => Yii::t('app', 'User ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(\app\models\base\Item::className(), ['location_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry0()
    {
        return $this->hasOne(\app\models\base\Country::className(), ['id' => 'country']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\app\models\base\User::className(), ['id' => 'user_id']);
    }
}
