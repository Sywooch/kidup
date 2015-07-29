<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "booking".
 *
 * @property integer $id
 * @property string $status
 * @property integer $item_id
 * @property integer $renter_id
 * @property integer $currency_id
 * @property string $refund_status
 * @property integer $time_from
 * @property integer $time_to
 * @property string $item_backup
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $payin_id
 * @property integer $payout_id
 * @property double $amount_payin
 * @property double $amount_payin_fee
 * @property double $amount_payin_fee_tax
 * @property double $amount_payin_costs
 * @property double $amount_item
 * @property double $amount_payout
 * @property double $amount_payout_fee
 * @property double $amount_payout_fee_tax
 * @property int $request_expires_at
 *
 * @property \app\models\base\Currency $currency
 * @property \app\models\base\Item $item
 * @property \app\models\base\User $renter
 * @property \app\models\base\Payin $payin
 * @property \app\models\base\Payout $payout
 * @property \app\models\base\Conversation[] $conversations
 * @property \app\models\base\Review[] $reviews
 */
class Booking extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'booking';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'time_from', 'time_to', 'updated_at', 'created_at'], 'required'],
            [['item_id', 'renter_id', 'currency_id', 'time_from', 'time_to', 'updated_at', 'created_at', 'payin_id', 'payout_id'], 'integer'],
            [['item_backup'], 'string'],
            [['amount_payin','amount_payin_fee','amount_payin_fee_tax','amount_payin_costs','amount_item','amount_payout','amount_payout_fee','amount_payout_fee_tax'], 'double'],
            [['status'], 'string', 'max' => 50],
            [['refund_status'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'status' => Yii::t('app', 'Status'),
            'item_id' => Yii::t('app', 'Item ID'),
            'renter_id' => Yii::t('app', 'Renter ID'),
            'currency_id' => Yii::t('app', 'Currency ID'),
            'refund_status' => Yii::t('app', 'Refund Status'),
            'time_from' => Yii::t('app', 'Time From'),
            'time_to' => Yii::t('app', 'Time To'),
            'item_backup' => Yii::t('app', 'Item Backup'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_at' => Yii::t('app', 'Created At'),
            'payin_id' => Yii::t('app', 'Payin ID'),
            'payout_id' => Yii::t('app', 'Payout ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(\app\models\base\Currency::className(), ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(\app\models\base\Item::className(), ['id' => 'item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRenter()
    {
        return $this->hasOne(\app\models\base\User::className(), ['id' => 'renter_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayin()
    {
        return $this->hasOne(\app\models\base\Payin::className(), ['id' => 'payin_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayout()
    {
        return $this->hasOne(\app\models\base\Payout::className(), ['id' => 'payout_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversations()
    {
        return $this->hasMany(\app\models\base\Conversation::className(), ['booking_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(\app\models\base\Review::className(), ['booking_id' => 'id']);
    }
}
