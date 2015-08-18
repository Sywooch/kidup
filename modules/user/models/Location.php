<?php

namespace app\modules\user\models;

use Carbon\Carbon;

class Location extends \app\models\base\Location
{
    const TYPE_MAIN = 1;

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            $this->created_at = Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp;
        }
        $this->updated_at = Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp;

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

    public static function getByIp($ip)
    {
        $buzz = new \Buzz\Browser(new \Buzz\Client\Curl());
        $adapter = new \Geocoder\HttpAdapter\BuzzHttpAdapter($buzz);
        $provider = new \Geocoder\Provider\FreeGeoIpProvider($adapter);
        $geocoder = new \Geocoder\Geocoder($provider);

        $address = $geocoder->geocode($ip);
        return $address;
    }

    public static function getIp()
    {
        if (YII_ENV == 'dev') {
            return "83.82.175.173";
        }
        $ip = getenv('HTTP_CLIENT_IP') ?:
            getenv('HTTP_X_FORWARDED_FOR') ?:
                getenv('HTTP_X_FORWARDED') ?:
                    getenv('HTTP_FORWARDED_FOR') ?:
                        getenv('HTTP_FORWARDED') ?:
                            getenv('REMOTE_ADDR');
        return $ip;
    }
}