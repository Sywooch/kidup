<?php
namespace codecept\muffins;

use Faker\Factory as Faker;
use notification\models\base\MobileDevices;

class MobileDeviceMuffin extends MobileDevices
{

    public function definitions()
    {
        $faker = Faker::create();
        return [];
    }

}
