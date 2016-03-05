<?php
namespace codecept\muffins;

use booking\models\payin\Payin;
use Codeception\Util\Debug;
use Faker\Factory as Faker;

class PayinMuffin extends Payin
{
    public function definitions()
    {
        $faker = Faker::create();
        return [
            'user_id' => 'factory|'.UserMuffin::class,
            'currency_id' => 'factory|'.CurrencyMuffin::class,
            'invoice_id' => 'factory|'.InvoiceMuffin::class,
            'status' => Payin::STATUS_AUTHORIZED,
            'nonce' => 'fake-valid-nonce',
            'amount' => $faker->numberBetween(10,10000)
        ];
    }
}
