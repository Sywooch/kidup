<?php
namespace app\modules\search\models;

use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "location".
 */
class IpLocation extends \app\models\base\IpLocation
{
    public static function get($ip)
    {
        $ipLocation = IpLocation::findOne(['ip' => $ip]);
        if ($ipLocation === null) {
            $buzz = new \Buzz\Browser(new \Buzz\Client\Curl());
            $adapter = new \Geocoder\HttpAdapter\BuzzHttpAdapter($buzz);
            $provider = new \Geocoder\Provider\FreeGeoIpProvider($adapter);
            $geocoder = new \Geocoder\Geocoder($provider);

            try{
                $address = $geocoder->geocode($ip);
            }catch(\Geocoder\Exception\NoResultException $e){
                $provider = new \Geocoder\Provider\HostIpProvider($adapter);
                $geocoder = new \Geocoder\Geocoder($provider);
                $address = $geocoder->geocode($ip);
            }
            $ipLocation = new IpLocation();
            $ipLocation->setAttributes([
                'ip' => $ip,
                'latitude' => $address->getLatitude(),
                'longitude' => $address->getLongitude(),
                'city' => $address->getCity(),
                'country' => $address->getCountry(),
                'street_name' => $address->getStreetName(),
                'street_number' => $address->getStreetNumber(),
                'data' => Json::encode($address->toArray())
            ]);
            $ipLocation->save();
        }

        return $ipLocation;

    }
}