<?php
namespace codecept\muffins;

use Faker\Factory as Faker;
use images\components\ImageHelper;

class ProfileMuffin extends \user\models\profile\Profile
{
    public function definitions()
    {
        $faker = Faker::create();
        return [
            'user_id' => 'factory|'.UserMuffin::class,
            'description' => $faker->text(200),
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'img' => ImageHelper::DEFAULT_USER_FACE,
            'phone_country' => '+31',
            'phone_number' => '6' . $faker->randomNumber(8),
            'identity_verified' => 1,
            'location_verified' => 1,
            'language' => 'da-DK',
            'currency_id' => 1,
            'nationality' => '1',
            'location_id' => 'factory|'.LocationMuffin::class
        ];
    }

}
