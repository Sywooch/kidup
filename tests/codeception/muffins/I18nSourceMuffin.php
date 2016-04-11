<?php
namespace codecept\muffins;

use admin\models\I18nSource;
use Faker\Factory as Faker;

class I18nSourceMuffin extends I18nSource
{
    public function definitions()
    {
        $faker = Faker::create();
        return [
            'category' => $faker->text(20),
            'message' => $faker->text(200)
        ];
    }

}
