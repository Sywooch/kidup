<?php
namespace codecept\muffins;

class TokenMuffin extends \notification\models\Token
{
    public function definitions()
    {
        $security = \Yii::$app->getSecurity();
        return [
            'user_id' => 'factory|'.UserMuffin::class,
            'code' => '1234',
            'type' => self::TYPE_PHONE_CODE
        ];
    }

}
