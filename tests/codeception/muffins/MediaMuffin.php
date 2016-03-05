<?php
namespace codecept\muffins;

use Faker\Factory as Faker;

class MediaMuffin extends \item\models\media\Media
{
    public function definitions()
    {
        $faker = Faker::create();

        return [
            'user_id' => 'factory|'.UserMuffin::className(),
            'file_name' => 'placehold.it/3x3',
        ];
    }

}
