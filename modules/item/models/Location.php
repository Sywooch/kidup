<?php

namespace item\models;

use booking\models\booking\Booking;
use Carbon\Carbon;
use user\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "location".
 */
class Location extends base\Location
{
    public $estimationRadius = 0;
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
        $this->updated_at = time();
        if ($this->isNewRecord) {
            $this->created_at = time();
        }
        if ($this->isAttributeChanged('street_name') ||
            $this->isAttributeChanged('street_number') ||
            $this->isAttributeChanged('city') ||
            $this->isAttributeChanged('zip_code') ||
            $this->isAttributeChanged('country')
        ) {
            $this->calculateLongitudeLatitude();
        }
        foreach (['city', 'zip_code', 'street_name', "zip_code", "street_name"] as $item) {
            $this->purifyIfChanges($item);
        }
        return parent::beforeValidate();
    }

    private function purifyIfChanges($attr)
    {
        if ($this->isAttributeChanged($attr)) {
            $this->street_name = \yii\helpers\HtmlPurifier::process($this->{$attr});
        }
    }

    private function calculateLongitudeLatitude()
    {
        $address = $this->getFormattedAddress();
        $res = Location::addressToLngLat($address);
        if (!$res) {
            $this->longitude = 0;
            $this->latitude = 0;
        } else {
            $this->longitude = $res['longitude'];
            $this->latitude = $res['latitude'];
        }
    }

    /**
     * Converts a human readable $address string into a longtiude, latitude array
     * @param string $address
     * @return array|bool
     */
    public static function addressToLngLat($address)
    {
        $request_url = "https://maps.googleapis.com/maps/api/geocode/xml?address=" . $address . "&sensor=true";
        $xml = simplexml_load_file($request_url);
        if ($xml === false) {
            return false;
        }
        $status = $xml->status;
        if ($status == "OK") {
            $lat = $xml->result->geometry->location->lat;
            $lon = $xml->result->geometry->location->lng;
            if (count($lat) > 0 && count($lon) > 0) {
                return [
                    'longitude' => reset($lon),
                    'latitude' => reset($lat),
                ];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function setStreetNameAndNumber($name)
    {
        $this->street_name = substr($name, 0, strcspn($name, '1234567890')); // gives foo
        $this->street_number = str_replace($this->street_name, '', $name);
        return $this;
    }

    public function isValid()
    {
        return $this->longitude != 0 && $this->latitude != 0;
    }

    /**
     * Whether use can access details of a location
     * @param Location $location
     * @param User|null $user
     * @return bool
     */
    public function canUserAccessDetails(User $user = null)
    {
        if (\Yii::$app->user->isGuest) {
            return false;
        }
        $user = is_null($user) ? \Yii::$app->user->identity : $user;
        if ($this->user_id == $user->id) {
            return true;
        }
        // see if the user rented this item
        $booking = Booking::find()
            ->where(['renter_id' => $user->id, 'location.id' => $this->id])
            ->innerJoinWith('item.location')
            ->count();
        return $booking > 0;
    }

    /**
     * Returns radius in which location is located
     * @return array
     */
    public function setLocationEstimation()
    {
        $this->longitude = $this->longitude * 1 + rand(-10, 10) / 10000;
        $this->latitude = $this->latitude * 1 + rand(-10, 10) / 10000;
        $this->estimationRadius = 0.01;
    }

    public function getFormattedAddress()
    {
        return $this->street_name . " " .
        $this->street_number . ", " .
        $this->city . " " .
        $this->zip_code . ", " .
        $this->country0->name;
    }
}
