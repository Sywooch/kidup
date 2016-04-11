<?php
namespace codecept\muffins;

use admin\models\I18nMessage;
use Faker\Factory as Faker;

class I18nMessageMuffin extends I18nMessage
{
    public function definitions()
    {
        $faker = Faker::create();
        return [
            'id' => 'factory|'.I18nSourceMuffin::class,
            'language' => 'da_DK',
            'translation' => $faker->text(200),
        ];
    }

}
