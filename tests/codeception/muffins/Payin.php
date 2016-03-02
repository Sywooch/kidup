<?php
namespace codecept\muffins;

use Codeception\Util\Debug;
use Faker\Factory as Faker;

class Payin extends \booking\models\Payin
{
    public function definitions()
    {
        $faker = Faker::create();
        return [
            'user_id' => 'factory|'.User::class,
            'currency_id' => 'factory|'.Currency::class,
            'invoice_id' => 'factory|'.Invoice::class,
            'status' => Payin::STATUS_AUTHORIZED,
            'nonce' => 'fake-valid-nonce',
            'amount' => $faker->numberBetween(10,10000)
        ];
    }
}
