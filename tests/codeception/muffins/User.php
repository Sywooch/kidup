<?php
namespace app\tests\codeception\muffins;

use League\FactoryMuffin\Faker\Facade as Faker;

class User extends \user\models\User
{
    public function definitions()
    {
        $security = \Yii::$app->getSecurity();
        return [
            'email' => Faker::email(),
            'auth_key' => $security->generateRandomString(),
            'password_hash' => '$2y$13$zSOTbjAPHZ7PloN466qJMO1DkCvLNsFLAdZEuKr/v.SbIh.xwLx4a',
        ];
    }
}
