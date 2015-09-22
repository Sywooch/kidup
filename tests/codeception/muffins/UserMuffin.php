<?php

use saada\FactoryMuffin\FactoryInterface;
use League\FactoryMuffin\Faker\Facade as Faker;

class User extends \app\models\base\User implements FactoryInterface
{
    public function definitions() {
        $security = Yii::$app->getSecurity();
        return [
            [
                'first_name'           => Faker::firstName(),
                'last_name'            => Faker::lastName(),
                'phone'                => Faker::phoneNumber(),
                'email'                => Faker::email(),
                'auth_key'             => $security->generateRandomString(),
                'password_hash'        => $security->generatePasswordHash('MyFixedTestUserPassword'),
                'password_reset_token' => $security->generateRandomString() . '_' . time(),
            ]
        ];
    }
}
