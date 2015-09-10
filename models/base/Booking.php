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
 * @property double $amount_item
 * @property double $amount_payin
 * @property double $amount_payin_fee
 * @property double $amount_payin_fee_tax
 * @property double $amount_payin_costs
 * @property double $amount_payout
 * @property double $amount_payout_fee
 * @property double $amount_payout_fee_tax
 * @property integer $request_expires_at
 * @property string $promotion_code_id
 *
 * @property \app\models\base\Currency $currency
 * @property \app\models\base\Item $item
 * @property \app\models\base\Payin $payin
 * @property \app\models\base\Payout $payout
 * @property \app\models\base\User $renter
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
            [['status', 'item_id', 'time_from', 'time_to', 'updated_at', 'created_at', 'request_expires_at'], 'required'],
            [['item_id', 'renter_id', 'currency_id', 'time_from', 'time_to', 'updated_at', 'created_at', 'payin_id', 'payout_id', 'request_expires_at'], 'integer'],
            [['item_backup'], 'string'],
            [['amount_item', 'amount_payin', 'amount_payin_fee', 'amount_payin_fee_tax', 'amount_payin_costs', 'amount_payout', 'amount_payout_fee', 'amount_payout_fee_tax'], 'number'],
            [['status'], 'string', 'max' => 50],
            [['refund_status'], 'string', 'max' => 20],
            [['promotion_code_id'], 'string', 'max' => 255],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['currency_id' => 'id']],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_id' => 'id']],
            [['payin_id'], 'exist', 'skipOnError' => true, 'targetClass' => Payin::className(), 'targetAttribute' => ['payin_id' => 'id']],
            [['payout_id'], 'exist', 'skipOnError' => true, 'targetClass' => Payout::className(), 'targetAttribute' => ['payout_id' => 'id']],
            [['renter_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['renter_id' => 'id']]
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
            'amount_item' => Yii::t('app', 'Amount Item'),
            'amount_payin' => Yii::t('app', 'Amount Payin'),
            'amount_payin_fee' => Yii::t('app', 'Amount Payin Fee'),
            'amount_payin_fee_tax' => Yii::t('app', 'Amount Payin Fee Tax'),
            'amount_payin_costs' => Yii::t('app', 'Amount Payin Costs'),
            'amount_payout' => Yii::t('app', 'Amount Payout'),
            'amount_payout_fee' => Yii::t('app', 'Amount Payout Fee'),
            'amount_payout_fee_tax' => Yii::t('app', 'Amount Payout Fee Tax'),
            'request_expires_at' => Yii::t('app', 'Request Expires At'),
            'promotion_code_id' => Yii::t('app', 'Promotion Code ID'),
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
    public function getRenter()
    {
        return $this->hasOne(\app\models\base\User::className(), ['id' => 'renter_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(\app\models\base\Review::className(), ['booking_id' => 'id']);
    }




}
