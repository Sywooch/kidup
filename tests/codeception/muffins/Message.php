<?php
namespace tests\muffins;

use Faker\Factory as Faker;

class Message extends \message\models\base\Message
{

    public function definitions()
    {
        $faker = Faker::create();
        return [
            'conversation_id' => 'factory|'.Conversation::class,
            'message' => $faker->text(200),
            'sender_user_id' => 'factory|'.User::class,
            'receiver_user_id' => 'factory|'.User::class,
            'created_at' => $faker->dateTimeBetween('-20 days', '-5 days')->getTimestamp(),
            'updated_at' => $faker->dateTimeBetween('-5 days', '-2 days')->getTimestamp(),
        ];
    }

}
