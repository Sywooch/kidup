<?php
namespace codecept\muffins;

class OauthAccessTokenMuffin extends \api\models\oauth\OauthAccessToken
{
    public function definitions()
    {
        return [
            'user_id' => 'factory|'.UserMuffin::class,
            'client_id' => 'factory|'.OauthClientMuffin::class,
            'access_token' => md5(rand(0,10000000000)), // doing the actual stuff is too heavy here
            'expires' => time()+24*60*60,
        ];
    }
}
