<?php

namespace api\models;

/**
 * This is the model class for table "item".
 */
class Booking extends \booking\models\Booking
{
    public function fields()
    {
        $fields = parent::fields();

        if($this->renter_id !== \Yii::$app->user->id && $this->item->owner_id !== \Yii::$app->user->id){
            return [];
        }

        return $fields;
    }

    public function extraFields()
    {
        return ['item'];
    }
}
