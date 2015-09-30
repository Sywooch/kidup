<?php

namespace user\models\base;

use Yii;

/**
 * This is the base-model class for table "ip_location".
 *
 * @property integer $id
 * @property string $ip
 * @property double $latitude
 * @property double $longitude
 * @property string $city
 * @property string $country
 * @property string $street_name
 * @property string $street_number
 * @property string $data
 */
class IpLocation extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ip_location';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ip'], 'required'],
            [['latitude', 'longitude'], 'number'],
            [['data'], 'string'],
            [['ip', 'city', 'country', 'street_name', 'street_number'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ip' => 'Ip',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'city' => 'City',
            'country' => 'Country',
            'street_name' => 'Street Name',
            'street_number' => 'Street Number',
            'data' => 'Data',
        ];
    }


    
}
