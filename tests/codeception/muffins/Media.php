<?php
namespace codecept\muffins;

use Faker\Factory as Faker;

class Media extends \item\models\Media
{
    public function definitions()
    {
        $faker = Faker::create();

        return [
            'user_id' => 'factory|'.User::className(),
            'file_name' => 'placehold.it/3x3',
        ];
    }

}
