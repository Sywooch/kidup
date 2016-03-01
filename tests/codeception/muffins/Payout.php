<?php
namespace codecept\muffins;

use Faker\Factory as Faker;

class Payout extends \user\models\base\Payout
{
    public function definitions()
    {
        $faker = Faker::create();
        return [
            'currency_id' => 'factory|'.Currency::class,
            'user_id' => 'factory|'.User::class,
            'invoice_id' => 'factory|'.Invoice::class,
            'amount' => $faker->numberBetween(1, 200),
            'processed_at' => $faker->dateTimeBetween('-10 days', 'now')->getTimestamp(),
            'created_at' => $faker->dateTimeBetween('-30 days', '-20 days')->getTimestamp(),
            'updated_at' => $faker->dateTimeBetween('-20 days', '-10 days')->getTimestamp(),
            'status' => '1'
        ];
    }

}
