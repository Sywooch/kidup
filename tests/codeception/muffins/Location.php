<?php
namespace app\tests\codeception\muffins;

use Codeception\Util\Debug;
use Faker\Factory as Faker;

class Location extends \app\models\base\Location
{
    public function definitions()
    {
        $faker = Faker::create();
        $precision = 100000;
        return [
            'type' => 1,
            'country' => 1,
            'city' => $faker->text(20),
            'zip_code' => $faker->numberBetween(1000, 9999) . '',
            'street_name' => $faker->text(20),
            'street_number' => $faker->numberBetween(1, 200) . '',
            'longitude' => $faker->numberBetween(-180 * $precision, 180 * $precision) / $precision,
            'latitude' => $faker->numberBetween(-90 * $precision, 90 * $precision) / $precision,
            'user_id' => 'factory|'.User::class,
            'created_at' => $faker->dateTimeBetween('-20 days', '-10 days')->getTimestamp(),
            'updated_at' => $faker->dateTimeBetween('-10 days', 'now')->getTimestamp(),
            'street_suffix' => ''
        ];
    }

}
