<?php

namespace api\models;

/**
 * This is the model class for table "item".
 */
class Review extends \review\models\Review
{
    public function fields()
    {
        if($this->is_public == 0){
            return [];
        }
        return parent::fields();
    }

    public function extraFields()
    {
        return ['reviewer', 'booking', 'item', 'reviewed'];
    }


    public function getBooking()
    {
        return $this->hasOne(Booking::className(), ['id' => 'booking_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviewer()
    {
        return $this->hasOne(User::className(), ['id' => 'reviewer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviewed()
    {
        return $this->hasOne(User::className(), ['id' => 'reviewed_id']);
    }

    public function getReviewer()
    {
        return $this->hasOne(User::className(), ['id' => 'reviewer_id']);
    }
}
