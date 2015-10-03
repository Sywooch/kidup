<?php
namespace app\tests\codeception\muffins;

use Faker\Factory as Faker;

class Media extends \item\models\Media
{
    public function definitions()
    {
        $faker = Faker::create();

        return [
            'user_id' => 'factory|'.User::className(),
            'storage' =>  Media::LOC_LOCAL,
            'type' => Media::TYPE_IMG,
            'file_name' => 'placehold.it/3x3',
        ];
    }

}
