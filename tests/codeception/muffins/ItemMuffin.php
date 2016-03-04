<?php
namespace codecept\muffins;

use Faker\Factory as Faker;

class ItemMuffin extends \item\models\Item
{

    public function definitions()
    {
        $faker = Faker::create();
        $priceDay = $faker->numberBetween(10, 1000);
        $priceWeek = (int)(rand(5 * 100, 7 * 100) / 100 * $priceDay);
        $priceMonth = (int)(rand(25 * 100, 30 * 100) / 100 * $priceDay);
        $priceYear = (int)(rand(200 * 100, 250 * 100) / 100 * $priceDay);
        return [
            'name' => $faker->text(50),
            'description' => $faker->text(200),
            'price_day' => $priceDay,
            'price_week' => $priceWeek,
            'price_month' => $priceMonth,
            'price_year' => $priceYear,
            'owner_id' => 'factory|'.UserMuffin::className(),
            'currency_id' => 1,
            'location_id' => 'factory|'.LocationMuffin::className(),
            'is_available' => 1,
            'category_id' => $faker->numberBetween(2, 6),
            'created_at' => $faker->dateTimeBetween('- 20 days', '- 10 days')->getTimestamp(),
            'updated_at' => $faker->dateTimeBetween('- 10 days', 'now')->getTimestamp(),
        ];
    }

}
