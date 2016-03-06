<?php
namespace codecept\muffins;

use Faker\Factory as Faker;
use notification\models\base\MobileDevices;

class MobileDeviceMuffin extends MobileDevices
{

    public function definitions()
    {
        $faker = Faker::create();
        return [
            'last_activity_at' => time(),
            'created_at' => time(),
            'device_id' => uniqid(),
            'token' => uniqid(),
            'platform' => 'test'
        ];
    }

}