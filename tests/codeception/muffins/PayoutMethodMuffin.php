<?php
namespace codecept\muffins;

use Faker\Factory as Faker;

class PayoutMethodMuffin extends \user\models\PayoutMethod
{
    public function definitions()
    {
        $faker = Faker::create();
        return [
            'payee_name' => $faker->name,
            'country_id' => 1,
            'type' => PayoutMethodMuffin::TYPE_DK_KONTO,
            'identifier_1' => "*******1234",
            'identifier_2' => "**32",
            'created_at' => $faker->dateTimeBetween('-30 days', '-20 days')->getTimestamp(),
            'updated_at' => $faker->dateTimeBetween('-20 days', '-10 days')->getTimestamp(),
        ];
    }

}
