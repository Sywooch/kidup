<?php
namespace app\tests\codeception\muffins;


class OauthAccessToken extends \api\models\oauth\OauthAccessToken
{
    public function definitions()
    {
        return [
            'user_id' => 'factory|'.User::class,
            'client_id' => 'testclient',
            'access_token' => md5(rand(0,10000000000)), // doing the actual stuff is too heavy here
            'expires' => time()+24*60*60,
        ];
    }

}
