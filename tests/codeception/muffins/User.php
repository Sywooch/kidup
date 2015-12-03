<?php
namespace codecept\muffins;

use League\FactoryMuffin\Faker\Facade as Faker;

class User extends \user\models\User
{
    public function definitions()
    {
        // Make it through RFC 2822
        $fakeEmail = Faker::freeEmail();
        return [
            'email' => $fakeEmail,
            // generating random stuff here every time would be pretty expensive
            'password_hash' => '$2y$13$zSOTbjAPHZ7PloN466qJMO1DkCvLNsFLAdZEuKr/v.SbIh.xwLx4a',
        ];
    }
}
