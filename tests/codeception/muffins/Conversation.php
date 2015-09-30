<?php
namespace app\tests\codeception\muffins;

use Faker\Factory as Faker;

class Conversation extends \message\models\base\Conversation
{

    public function definitions()
    {
        $faker = Faker::create();
        return [
            'initiater_user_id' => 'factory|'.User::class,
            'target_user_id' => 'factory|'.User::class,
            'title' => $faker->text(50),
            'created_at' => $faker->dateTimeBetween('-20 days', '-5 days')->getTimestamp(),
            'updated_at' => $faker->dateTimeBetween('-5 days', '-2 days')->getTimestamp(),
            'booking_id' => 'factory|'.Booking::class,
        ];
    }

}
