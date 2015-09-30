<?php
namespace app\tests\codeception\muffins;

use Faker\Factory as Faker;

class Currency extends \user\models\base\Currency
{

    public function definitions()
    {
        $faker = Faker::create();
        return [
            'name' => $faker->text(10),
            'abbr' => $faker->text(5),
            'forex_name' => $faker->text(5),
        ];
    }

}
