<?php

namespace app\modules\item\models;

use Carbon\Carbon;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "location".
 */
class Location extends \app\models\base\Location
{

    const TYPE_MAIN = 1;
    const TYPE_ITEM = 2;

    public function rules()
    {
        return [
            [['type', 'country', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['longitude', 'latitude', 'user_id', 'created_at', 'updated_at'], 'required'],
            [['longitude', 'latitude'], 'number'],
            [['city'], 'string', 'max' => 100],
            [['zip_code'], 'string', 'max' => 50],
            [['street_name'], 'string', 'max' => 256],
            [['street_number'], 'string', 'max' => 10],
            [['street_suffix'], 'string', 'max' => 50]
        ];
    }

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
            if (!$res) {
                $this->longitude = 1;
                $this->latitude = 1;
            } else {
                $this->longitude = $res['longitude'];
                $this->latitude = $res['latitude'];
            }
        }
        if ($this->isAttributeChanged('city')) {
            $this->city = \yii\helpers\HtmlPurifier::process($this->city);
        }
        if ($this->isAttributeChanged('zip_code')) {
            $this->zip_code = \yii\helpers\HtmlPurifier::process($this->zip_code);
        }
        if ($this->isAttributeChanged('street_name')) {
            $this->street_name = \yii\helpers\HtmlPurifier::process($this->street_name);
        }
        if ($this->isAttributeChanged('zip_code')) {
            $this->zip_code = \yii\helpers\HtmlPurifier::process($this->zip_code);
        }
        if ($this->isAttributeChanged('street_name')) {
            $this->street_name = \yii\helpers\HtmlPurifier::process($this->street_name);
        }
        $this->updated_at = time();
        if($this->isNewRecord){
            $this->created_at = time();
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

    public function setStreetNameAndNumber($name){
        $this->street_name = substr($name, 0, strcspn($name, '1234567890')); // gives foo
        $this->street_number = str_replace($this->street_name, '', $name);
        return $this;
    }

    public function isValid()
    {
        if ($this->longitude != 0 && $this->latitude != 0) {
            return true;
        }
        return false;
    }
}
