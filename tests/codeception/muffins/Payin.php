<?php
namespace app\tests\codeception\muffins;

use Faker\Factory as Faker;

class Payin extends \booking\models\base\Payin
{
    public function definitions()
    {
        $faker = Faker::create();
        return [
            'user_id' => 'factory|'.User::class,
            'currency_id' => 'factory|'.Currency::class,
            'invoice_id' => 'factory|'.Invoice::class,
        ];
    }

}
