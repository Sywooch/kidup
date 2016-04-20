<?php

namespace item\models\location;

use item\models\location\Location;
use user\models\country\Country;
use Yii;
use yii\db\ActiveRecord;


class LocationError extends \app\components\Exception
{
}

;

/**
 * This is the model class for table "location".
 */
class LocationFactory
{
    /**
     * Creates a location by latitude longitude
     * @param $latitude
     * @param $longitude
     * @return array|bool|Location|null|ActiveRecord
     */
    public static function createByLatLong($latitude, $longitude, $save = false)
    {
        $latitude = round($latitude, 5);
        $longitude = round($longitude, 5);
        // find an approximately same location
        $loc = Location::find()->where(['user_id' => \Yii::$app->user->id])
            ->andWhere("latitude >= :latLower and latitude <= :latUpper and longitude >= :lngLower and longitude <= :lngUpper")
            ->addParams([
                ':latLower' => $latitude - 0.006,
                ':latUpper' => $latitude + 0.006,
                ':lngLower' => $longitude - 0.006,
                ':lngUpper' => $longitude + 0.006
            ])
            ->one();
        if ($loc !== null) {
            return $loc;
        }
        $request_url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=" . $latitude
            . ',' . $longitude . "&sensor=true";
        $json = json_decode(file_get_contents($request_url), true);
        if (count($json['results']) == 0) {
            throw new LocationError("Lng lat combination not found on google.");
        }
        // assume the first is the best?
        $address = $json['results'][0];
        $location = self::createByGoogleAddressComponents($address['address_components']);
        $location->country = 1;
        $location->latitude = $latitude;
        $location->longitude = $longitude;
        if ($save) {
            $location->user_id = \Yii::$app->user->id;
            $location->save();
        }
        return $location;
    }

    /**
     * creates a location by converting google address components
     * @param $components
     * @return Location
     */
    public static function createByGoogleAddressComponents($components, $save = false)
    {
        $location = new Location();
        foreach ($components as $component) {
            if (in_array("postal_code", $component['types'])) {
                $location->zip_code = $component['long_name'];
            }
            if (in_array("locality", $component['types'])) {
                $location->city = $component['long_name'];
            }
            if (in_array("route", $component['types'])) {
                $location->street_name = $component['long_name'];
            }
            if (in_array("street_number", $component['types'])) {
                $location->street_number = $component['long_name'];
            }
        }
        if ($save) {
            $location->user_id = \Yii::$app->user->id;
            $location->save();
        }
        return $location;
    }

    /**
     * A method which works offline and does not depend on external connection for fetching a location based
     * on an IP address.
     *
     * @param $ip string
     * @return Location
     */
    public static function createByIp($ip, $save = false)
    {
        if (strpos($ip, '.') !== false) {
            // its an v4 address
            $gi = geoip_open(Yii::$aliases['@item'] . "/data/GeoLiteCity.dat", GEOIP_STANDARD);
            $record = GeoIP_record_by_addr($gi, $ip);
            geoip_close($gi);
        } else {
            // its an v6 address
            $gi = geoip_open(Yii::$aliases['@item'] . "/data/GeoLiteCityv6.dat", GEOIP_STANDARD);
            $record = GeoIP_record_by_addr_v6($gi, $ip);
            geoip_close($gi);
        }
        if ($record === null) {
            return false;
        }
        $location = new Location();
        $location->zip_code = $record->postal_code;
        $location->street_name = '';
        $location->street_number = '';
        $location->street_suffix = '';
        $location->city = $record->city;
        $location->longitude = $record->longitude;
        $location->latitude = $record->latitude;
        $country = Country::findOne(['code' => $record->country_code]);
        if ($country != null) {
            $location->country = $country->id;
        } else {
            $location->country = 1;
        }
        if ($save) {
            $location->user_id = \Yii::$app->user->id;
            $location->save();
        }
        return $location;
    }
}
