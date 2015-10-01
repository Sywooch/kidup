<?php
namespace app\tests\codeception\muffins;

use League\FactoryMuffin\Faker\Facade as Faker;

class User extends \user\models\User
{
    public function definitions()
    {
        return [
            'email' => Faker::email(),
            // generating random stuff here every time would be pretty expensive
            'auth_key' => 'some-lame-auth-key',
            'password_hash' => '$2y$13$zSOTbjAPHZ7PloN466qJMO1DkCvLNsFLAdZEuKr/v.SbIh.xwLx4a',
        ];
    }
}
