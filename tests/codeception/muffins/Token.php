<?php
namespace tests\muffins;

class Token extends \mail\models\Token
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
