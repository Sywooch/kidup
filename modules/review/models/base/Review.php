<?php

namespace review\models\base;

use booking\models\booking\Booking;
use item\models\item\Item;
use user\models\user\User;
use Yii;

/**
 * This is the base-model class for table "review".
 *
 * @property integer $id
 * @property string $value
 * @property integer $reviewer_id
 * @property integer $reviewed_id
 * @property string $type
 * @property integer $booking_id
 * @property integer $item_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_public
 *
 * @property Booking $booking
 * @property \item\models\item\Item $item
 * @property User $reviewer
 * @property User $reviewed
 */
class Review extends \app\components\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'review';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value'], 'string'],
            [['reviewer_id', 'reviewed_id', 'type', 'created_at'], 'required'],
            [['reviewer_id', 'reviewed_id', 'booking_id', 'item_id', 'created_at', 'updated_at', 'is_public'], 'integer'],
            [['type'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'value' => 'Value',
            'reviewer_id' => 'Reviewer ID',
            'reviewed_id' => 'Reviewed ID',
            'type' => 'Type',
            'booking_id' => 'Booking ID',
            'item_id' => 'Item ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBooking()
    {
        return $this->hasOne(\booking\models\booking\Booking::className(), ['id' => 'booking_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(\item\models\item\Item::className(), ['id' => 'item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviewer()
    {
        return $this->hasOne(\user\models\user\User::className(), ['id' => 'reviewer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviewed()
    {
        return $this->hasOne(\user\models\user\User::className(), ['id' => 'reviewed_id']);
    }
}
