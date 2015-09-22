<?php
namespace app\tests\codeception\muffins;

use app\modules\images\components\ImageHelper;
use Faker\Factory as Faker;

class Item extends \app\modules\item\models\Item
{
    public function definitions()
    {
        $faker = Faker::create();
        return [
            'name' => $faker->text(50),
            'description' => $faker->text(200),
            'price_week' => $faker->numberBetween(10, 1000),
            'owner_id' => 'factory|' . User::class,
            'currency_id' => 1,
            'is_available' => 1,
            'category_id' => 4,
            'min_renting_days' => 0
        ];
    }
}
