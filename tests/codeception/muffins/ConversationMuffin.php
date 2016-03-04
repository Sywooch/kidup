<?php
namespace codecept\muffins;

use booking\models\booking\Booking;
use Faker\Factory as Faker;

class ConversationMuffin extends \message\models\conversation\Conversation
{

    public function definitions()
    {
        $faker = Faker::create();
        return [
            'initiater_user_id' => 'factory|'.UserMuffin::class,
            'target_user_id' => 'factory|'.UserMuffin::class,
            'title' => $faker->text(50),
            'created_at' => $faker->dateTimeBetween('-20 days', '-5 days')->getTimestamp(),
            'updated_at' => $faker->dateTimeBetween('-5 days', '-2 days')->getTimestamp(),
            'booking_id' => 'factory|'.Booking::class,
        ];
    }

}
