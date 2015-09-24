<?php
namespace app\tests\codeception\muffins;

use League\FactoryMuffin\Faker\Facade as Faker;

class Token extends \app\modules\mail\models\Token
{
    public function definitions()
    {
        $security = \Yii::$app->getSecurity();
        return [
            'user_id' => 'factory|'.User::class,
            'code' => '1234',
            'type' => self::TYPE_PHONE_CODE
        ];
    }

}
