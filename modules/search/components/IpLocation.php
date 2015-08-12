<?php
namespace app\modules\search\components;

use app\components\Cache;
use Yii;
use yii\base\Component;
use yii\helpers\Json;

/**
 * This is the model class for table "location".
 */
class IpLocation extends Component
{
    public static function get($ip)
    {
        return Cache::data('ip'. $ip, function() use ($ip){
            $buzz = new \Buzz\Browser(new \Buzz\Client\Curl());
            $adapter = new \Geocoder\HttpAdapter\BuzzHttpAdapter($buzz);
            $provider = new \Geocoder\Provider\FreeGeoIpProvider($adapter);
            $geocoder = new \Geocoder\Geocoder($provider);
            $address = $geocoder->geocode($ip);
            $location = [
                'ip' => $ip,
                'latitude' => $address->getLatitude(),
                'longitude' => $address->getLongitude(),
                'city' => $address->getCity(),
                'country' => $address->getCountry(),
                'street_name' => $address->getStreetName(),
                'street_number' => $address->getStreetNumber(),
                'data' => Json::encode($address->toArray())
            ];
            return $location;
        }, 6*30*24*60*60);
    }

    public static function getIp()
    {
        if (YII_ENV == 'dev') {
            // something else then localhost
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