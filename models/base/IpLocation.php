<?php

namespace app\models\base;

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
            'id' => Yii::t('app', 'ID'),
            'ip' => Yii::t('app', 'Ip'),
            'latitude' => Yii::t('app', 'Latitude'),
            'longitude' => Yii::t('app', 'Longitude'),
            'city' => Yii::t('app', 'City'),
            'country' => Yii::t('app', 'Country'),
            'street_name' => Yii::t('app', 'Street Name'),
            'street_number' => Yii::t('app', 'Street Number'),
            'data' => Yii::t('app', 'Data'),
        ];
    }


    
}
