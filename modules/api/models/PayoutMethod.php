<?php

namespace api\models;

/**
 * This is the model class for table "item".
 */
class PayoutMethod extends \user\models\PayoutMethod
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
        }
        return parent::beforeValidate();
    }

}
