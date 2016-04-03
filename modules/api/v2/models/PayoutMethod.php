<?php

namespace api\v2\models;
use app\helpers\Encrypter;

/**
 * This is the model class for table "item".
 */
class PayoutMethod extends \user\models\payoutMethod\PayoutMethod
{
    public function fields()
    {
        $fields = parent::fields();

        return $fields;
    }

    public function beforeValidate()
    {
        if (!isset($this->user_id)) {
            $this->user_id = \Yii::$app->user->id;
            $this->country_id = 1;
            $this->type = PayoutMethod::TYPE_DK_KONTO;

            $this->bank_name = 'unknown';

            $this->identifier_1 = $this->transformToSafe($this->identifier_1, 4);
            $this->identifier_1_encrypted = \app\helpers\Encrypter::encrypt($this->identifier_1_encrypted,
                Encrypter::SIZE_1024);
            $this->identifier_2 = $this->transformToSafe($this->identifier_2, 2);
            $this->identifier_2_encrypted = \app\helpers\Encrypter::encrypt($this->identifier_2_encrypted,
                Encrypter::SIZE_1024);
        }
        return parent::beforeValidate();
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
    }

  

}
