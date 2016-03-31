<?php

namespace api\models;

use user\models\user\User;

/**
 * This is the model class for table "item".
 */
class Booking extends \booking\models\booking\Booking
{
    public function fields()
    {
        $fields = parent::fields();

        if($this->renter_id !== \Yii::$app->user->id && $this->item->owner_id !== \Yii::$app->user->id){
            return [];
        }

        if($this->renter_id === \Yii::$app->user->id){
            unset($fields['amount_payout']);
            unset($fields['amount_payout_fee']);
            unset($fields['amount_payout_fee_tax']);
        }
        unset($fields['amount_payin_costs']);

        if($this->item->owner_id === \Yii::$app->user->id){
            unset($fields['amount_payin']);
            unset($fields['amount_payin_fee']);
            unset($fields['amount_payin_fee_tax']);
        }

        $fields['conversation_id'] = function($model){
            /**
             * @var Booking $model
             */
            if($model->conversation){
                return $model->conversation->id;
            }
            return null;
        };

        return $fields;
    }

    public function extraFields()
    {
        return ['item', 'conversation', 'renter'];
    }

    public function getConversation()
    {
        return $this->hasOne(Conversation::className(), ['booking_id' => 'id']);
    }

    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }

    public function getRenter()
    {
        return $this->hasOne(User::className(), ['id' => 'renter_id']);
    }
}
