<?php
namespace app\tests\codeception\muffins;

use Codeception\Util\Debug;
use Faker\Factory as Faker;

class Booking extends \booking\models\Booking
{
    public function definitions()
    {
        $faker = Faker::create();
        return [
            'status' => self::AWAITING_PAYMENT,
            'item_id' => 'factory|'.Item::class,
            'renter_id' => 'factory|'.User::class,
            'currency_id' => 'factory|'.Currency::class,
            'refund_status' => '1',
            'time_from' => $faker->dateTimeBetween('+30 days', '+40 days')->getTimestamp(),
            'time_to' => $faker->dateTimeBetween('+40 days', '+50 days')->getTimestamp(),
            'item_backup' => '',
            'updated_at' => $faker->dateTimeBetween('-5 days', '-1 days')->getTimestamp(),
            'created_at' => $faker->dateTimeBetween('-10 days', '-5 days')->getTimestamp(),
            'payin_id' => 'factory|'.Payin::class,
            'payout_id' => 'factory|'.Payout::class,
            'amount_item' => $faker->numberBetween(1, 200),
            'amount_payin' => $faker->numberBetween(1, 5),
            'amount_payin_fee' => $faker->numberBetween(1, 5),
            'amount_payin_fee_tax' => $faker->numberBetween(1, 5),
            'amount_payin_costs' => $faker->numberBetween(1, 5),
            'amount_payout' => $faker->numberBetween(1, 200),
            'amount_payout_fee' => $faker->numberBetween(1, 5),
            'amount_payout_fee_tax' => $faker->numberBetween(1, 5),
            'request_expires_at' => $faker->dateTimeBetween('+ 3 days', '+ 8 days')->getTimestamp(),
            'promotion_code_id' => '0'
        ];
    }

    public static function callback($object, $saved){
    }
}
