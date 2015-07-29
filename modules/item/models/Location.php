<?php

namespace app\modules\item\models;

use Yii;
use Carbon\Carbon;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "location".
 */
class Location extends \app\models\base\Location
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => function () {
                    return Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp;
                }
            ],
        ];
    }

    const TYPE_MAIN = 1;

    public function beforeValidate()
    {
        if ($this->isAttributeChanged('street_name') ||
            $this->isAttributeChanged('street_number') ||
            $this->isAttributeChanged('city') ||
            $this->isAttributeChanged('zip_code') ||
            $this->isAttributeChanged('country')
        ) {
            $buzz = new \Buzz\Browser(new \Buzz\Client\Curl());
            $adapter = new \Geocoder\HttpAdapter\BuzzHttpAdapter($buzz);
            $provider = new \Geocoder\Provider\YandexProvider($adapter);
            $geocoder = new \Geocoder\Geocoder($provider);
            if (empty($this->street_name) or
                empty($this->city) or
                empty($this->country)
            ) {
                return parent::beforeValidate();
            }
            $address = $this->street_name . " " .
                $this->street_number . ", " .
                $this->city . " " .
                $this->zip_code . ", " .
                $this->country0->name;
            $res = Location::getByAddress($address);
            if(!$res){
                $this->longitude = 1;
                $this->latitude = 1;
            }else{
                $this->longitude = $res['longitude'];
                $this->latitude = $res['latitude'];
            }
        }
        return parent::beforeValidate();
    }

    public static function getByAddress($address)
    {
        $buzz = new \Buzz\Browser(new \Buzz\Client\Curl());
        $adapter = new \Geocoder\HttpAdapter\BuzzHttpAdapter($buzz);
        $provider = new \Geocoder\Provider\YandexProvider($adapter);
        $geocoder = new \Geocoder\Geocoder($provider);

        try {
            $address = $geocoder->geocode($address);
            return [
                'longitude' => $address->getLongitude(),
                'latitude' => $address->getLatitude(),
            ];
        } catch (\Geocoder\Exception\NoResultException $e) {
            return false;
        }
    }

    public function isValid(){
        if($this->longitude != 0 && $this->latitude != 0){
            return true;
        }
        return false;
    }
}
