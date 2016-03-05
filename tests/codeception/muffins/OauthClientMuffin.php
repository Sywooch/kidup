<?php
namespace codecept\muffins;

use Faker\Factory as Faker;

class OauthClientMuffin extends \api\models\oauth\OauthClient
{
    public function definitions()
    {
        $faker = Faker::create();
        return [
            'client_id' => $faker->userName,
            'client_secret' => $faker->password(6),
            'redirect_uri' => $faker->url,
            'grant_types' => 'test'
        ];
    }

}
