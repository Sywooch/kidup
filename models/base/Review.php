<?php

namespace app\models\base;

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
 * @property \app\models\User $reviewed
 * @property \app\models\Booking $booking
 * @property \app\models\Item $item
 * @property \app\models\User $reviewer
 */
class Review extends \yii\db\ActiveRecord
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
            [['reviewer_id', 'reviewed_id', 'type', 'booking_id', 'created_at'], 'required'],
            [['reviewer_id', 'reviewed_id', 'booking_id', 'item_id', 'created_at', 'updated_at', 'is_public'], 'integer'],
            [['type'], 'string', 'max' => 45],
            [['reviewed_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['reviewed_id' => 'id']],
            [['booking_id'], 'exist', 'skipOnError' => true, 'targetClass' => Booking::className(), 'targetAttribute' => ['booking_id' => 'id']],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_id' => 'id']],
            [['reviewer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['reviewer_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'value' => Yii::t('app', 'Value'),
            'reviewer_id' => Yii::t('app', 'Reviewer ID'),
            'reviewed_id' => Yii::t('app', 'Reviewed ID'),
            'type' => Yii::t('app', 'Type'),
            'booking_id' => Yii::t('app', 'Booking ID'),
            'item_id' => Yii::t('app', 'Item ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'is_public' => Yii::t('app', 'Is Public'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviewed()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'reviewed_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBooking()
    {
        return $this->hasOne(\app\models\Booking::className(), ['id' => 'booking_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(\app\models\Item::className(), ['id' => 'item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviewer()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'reviewer_id']);
    }




}
