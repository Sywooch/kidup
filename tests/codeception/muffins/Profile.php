<?php
namespace app\tests\codeception\muffins;

use app\modules\images\components\ImageHelper;
use Codeception\Util\Debug;
use Faker\Factory as Faker;

class Profile extends \app\modules\user\models\Profile
{
    public function definitions()
    {
        $faker = Faker::create();
        return [
            'user_id' => 'factory|'.User::class,
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
            'location_id' => 'factory|'.Location::class
        ];
    }

}
